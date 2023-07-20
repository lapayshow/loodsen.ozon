<?php

namespace Loodsen\Ozon\Services;

use Bitrix\Forum\File;
use Bitrix\Iblock\Iblock;
use Bitrix\Main\Loader;
use Bitrix\Main\Result;
use Loodsen\Ozon\Models\Catalog\OzonCatalogProductModel;
use Loodsen\Ozon\Models\Catalog\OzonCatalogSectionModel;
use Loodsen\Ozon\Services\Catalog\OzonCatalogSectionService;
use Loodsen\Ozon\Services\Catalog\OzonCatalogProductService;
use Bx\Model\Services\FileService;
use Bitrix\Iblock\EO_Section;
use Loodsen\Ozon\References\ConfigList;
use CUtil;

class OzonImportService
{
  protected $ozonCatalogSectionService;
  protected $importResult;

  public function __construct()
  {
    Loader::includeModule('iblock');
    $this->ozonCatalogSectionService = new OzonCatalogSectionService();
    $fileService = new FileService();
    $this->ozonCatalogProductService = new OzonCatalogProductService($fileService);
  }

  public function saveCategoryList(array $categoryList, $iblockSectionId = null): ?array
  {
    $ozonCatalogSectionService = $this->ozonCatalogSectionService;

    $iblockId = $ozonCatalogSectionService->getIblockId();

    $result = [];
    foreach ($categoryList as $category) {
      if (!empty($category['category_id'])) {
        if (!empty($category['title'])) {
          $translitCategoryTitle = CUtil::translit($category['title'], 'ru', [100, 'L', '-', '-']);
        }

        $model = new OzonCatalogSectionModel([
         'NAME' => $category['title'],
         'CODE' => $translitCategoryTitle,
         'IBLOCK_ID' => $iblockId,
         'IBLOCK_SECTION_ID' => $iblockSectionId, // Id родительского раздела
         'UF_CATEGORY_ID_OZON' => $category['category_id'],
        ]);

        $result = $ozonCatalogSectionService->save($model);

        // Если ошибка записи, принудительно проверяем символьные коды
        // новый это раздел или старый, если новый - записываем с правками translitCategoryTitle
        if ($result->isSuccess() == false) {
          $sectionExist = $ozonCatalogSectionService->getList([
           'filter' => [
              'UF_CATEGORY_ID_OZON' => (string) $category['category_id'],
           ],
          ])->first();
          if (empty($sectionExist)) {
            $model->setCode($translitCategoryTitle . '_' . $category['category_id']);
            $result = $ozonCatalogSectionService->save($model);
          }
        }

        if ($result->isSuccess() == true) {
          $this->importResult['success']++;
        } else {
          if (!empty($sectionExist)) {
            $this->importResult['exists']++;
            $this->importResult['sectionList'][] = [
             $category['category_id'],
             $category['title'],
            ];
          } else {
            $this->importResult['errors'][] = $result->getErrorMessages();
          }
        }

        if (!empty($category['children'])) {
          $parentId = $ozonCatalogSectionService->getList([
           'filter' => ['UF_CATEGORY_ID_OZON' => (string) $category['category_id']]
          ])->first()->getValueByKey('ID');

          $this->saveCategoryList($category['children'], iblockSectionId: $parentId);
        }
      }
    }

    return $this->importResult;
  }

  public function saveProductList($productList): ?array
  {
    $ozonCatalogProductService = $this->ozonCatalogProductService;

    $result = [];
    foreach ($productList as $product) {
      if (!empty($product['name'])) {
        $translitCategoryTitle = CUtil::translit($product['name'], 'ru', [200, 'L', '-', '-']);
      }
      $sectionId = $this->getSectionIdByOzonCategoryId($product['category_id']);

      $attributeCode = ConfigList::ATTRIBUTES;

      foreach ($product['attributes'] as $attribute) {
        switch ($attribute['attribute_id']) {
          case $attributeCode['EQUIPMENT']: $product['EQUIPMENT'] = current($attribute['values'])['value']; break;
          case $attributeCode['TYPE']: $product['TYPE'] = current($attribute['values'])['value']; break;
          case $attributeCode['PARTNUMBER']: $product['PARTNUMBER'] = current($attribute['values'])['value']; break;
          case $attributeCode['ARTIKUL']: $product['ARTIKUL'] = current($attribute['values'])['value']; break;
          case $attributeCode['TECHNIQUE_TYPE']:
            if (count($attribute['values']) > 1) {
              foreach ($attribute['values'] as $value) {
                $product['TECHNIQUE_TYPE'] = empty($product['TECHNIQUE_TYPE']) ?
                 $value['value'] : $product['TECHNIQUE_TYPE'] . ', ' . $value['value'];
              }
            } else {
              $product['TECHNIQUE_TYPE'] = current($attribute['values'])['value'];
            }
            break;
          case $attributeCode['DESCRIPTION']: $product['DESCRIPTION'] = current($attribute['values'])['value']; break;
          case $attributeCode['MARKA_TS']: $product['MARKA_TS'] = current($attribute['values'])['value']; break;
          case $attributeCode['MODELTS']: $product['MODELTS'] = current($attribute['values'])['value']; break;
          case $attributeCode['STRANA_IZGOTOVITEL']: $product['STRANA_IZGOTOVITEL'] = current($attribute['values'])['value']; break;
          case $attributeCode['BRAND']: $product['BRAND'] = current($attribute['values'])['value']; break;
          case $attributeCode['GARANTY_TERM']: $product['GARANTY_TERM'] = current($attribute['values'])['value']; break;
          case $attributeCode['AMOUNT_PACKAGE']: $product['AMOUNT_PACKAGE'] = current($attribute['values'])['value']; break;
        }
      }

      $model = new OzonCatalogProductModel([
       'NAME' => $product['name'],
       'CODE' => $translitCategoryTitle,
       'IBLOCK_SECTION_ID' => $sectionId,
       'PREVIEW_PICTURE' => '',
       'DETAIL_PICTURE' => '',
       'DETAIL_TEXT' => $product['DESCRIPTION'],
       'PRODUCT_ID_VALUE' => $product['id'],
       'OFFER_ID_VALUE' => $product['offer_id'],
       'BRAND_VALUE' => $product['BRAND'],
       'PARTNUMBER_VALUE' => $product['PARTNUMBER'],
       'MARKA_TS_VALUE' => $product['MARKA_TS'],
       'MODELTS_VALUE' => $product['MODELTS'],
       'TYPE_VALUE' => $product['TYPE'],
       'ARTIKUL_VALUE' => $product['ARTIKUL'],
       'STRANA_IZGOTOVITEL_VALUE' => $product['STRANA_IZGOTOVITEL'],
       'GARANTY_TERM_VALUE' => $product['GARANTY_TERM'],
       'TECHNIQUE_TYPE_VALUE' => $product['TECHNIQUE_TYPE'],
       'AMOUNT_PACKAGE_VALUE' => $product['AMOUNT_PACKAGE'],
       'EQUIPMENT_VALUE' => $product['EQUIPMENT'],
       'ALTERNATE_ARTIKUL_VALUE' => '',
       'AMOUNT_VALUE' => '',
       'CARVING_VALUE' => '',
       "DISCOUNT_VALUE" => '',
       "HEIGHT_VALUE" => $product['height'],
       "WIDTH_VALUE" => $product['width'],
       "DEPTH_VALUE" => $product['depth'],
       "DIMENSION_UNIT_VALUE" => $product['dimension_unit'],
       "WEIGHT_VALUE" => $product['weight'],
       "WEIGHT_UNIT_VALUE" => $product['weight_unit'],
       "CURRENCY_CODE_VALUE" => $product['product_info']['currency_code'],
       "PRICE_VALUE" => $product['product_info']['price'],
       "MARKETING_PRICE_VALUE" => $product['product_info']['marketing_price'],
       "OLD_PRICE_VALUE" => $product['product_info']['old_price'],
       "MIN_PRICE_VALUE" => $product['product_info']['min_price'],
      ]);

      foreach ($product['images'] as $image) {
        $model['IMAGES_LIST_VALUE'] = (empty($model['IMAGES_LIST_VALUE'])) ? $image['file_name'] : $model['IMAGES_LIST_VALUE'] . '|' . $image['file_name'];
      }

      $elementExist = $ozonCatalogProductService->getList([
       'filter' => [
        'PRODUCT_ID_VALUE' => $product['id']
       ],
      ])->first();
      if (!empty($elementExist)) {
        $elementId = $elementExist->getValueByKey('ID');
        $model->setId($elementId);
      }

      if ($sectionId > 0) {
        $result = $ozonCatalogProductService->save($model);
      }

      if ($result->isSuccess() == true && $sectionId > 0) {
        if (!empty($elementExist)) {
          $this->importResult['exists']++;
          $this->importResult['productList'][] = [
           $product['id'],
           $product['name'],
          ];
        } else {
          $this->importResult['success']++;
        }
      } else {
        $this->importResult['errors'][] = $result->getErrorMessages();
      }
    }

    return $this->importResult;
  }

  protected function getSectionIdByOzonCategoryId($categoryId): ?string
  {
    $result = $this->ozonCatalogSectionService->getList([
     'filter' => [
      'UF_CATEGORY_ID_OZON' => (string) $categoryId,
     ],
    ])->first();

    if (!empty($result)) {
     return (int) $result->getValueByKey('ID');
    }

    return '';
  }
}
