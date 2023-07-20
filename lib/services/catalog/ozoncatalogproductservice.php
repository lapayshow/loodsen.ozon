<?php

namespace Loodsen\Ozon\Services\Catalog;

use Bitrix\Iblock\Elements\EO_ElementCatalogozon;
use Bitrix\Iblock\Elements\ElementCatalogozonTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Error;
use Bitrix\Main\ORM\Objectify\State;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\Result;
use Bitrix\Main\SystemException;
use Bx\Model\AbsOptimizedModel;
use Bx\Model\BaseLinkedModelService;
use Bx\Model\FetcherModel;
use Bx\Model\Interfaces\ModelServiceInterface;
use Bx\Model\Interfaces\Models\IblockServiceInterface;
use Bx\Model\Interfaces\UserContextInterface;
use Bx\Model\ModelCollection;
use Bx\Model\Traits\IblockServiceTrait;
use Exception;
use Loodsen\Ozon\Models\Catalog\OzonCatalogProductModel;
use CIBlockElement;
use Loodsen\Ozon\References\ApiMethodsList;
use Loodsen\Ozon\References\ConfigList;
use COption;

class OzonCatalogProductService extends BaseLinkedModelService implements IblockServiceInterface
{
  use IblockServiceTrait;

  /**
   * @var ModelServiceInterface
   */
  public $fileService;
  private $iblockId;


  public function __construct(ModelServiceInterface $fileService)
  {
    $this->fileService = $fileService;
  }


  public function getIblockId(): int
  {
    if (!empty($this->iblockId)) {
      return $this->iblockId;
    }

    return $this->iblockId = (int)ElementCatalogozonTable::getEntity()->getIblock()->getId();
  }


  protected function getLinkedFields(): array
  {
    return [
     'preview_file' => new FetcherModel(
      $this->fileService,
      'preview_file',
      'PREVIEW_PICTURE',
      'ID'
     ),
     'detail_file' => new FetcherModel(
      $this->fileService,
      'detail_file',
      'DETAIL_PICTURE',
      'ID'
     ),
    ];
  }


  /**
   * @param array $params
   * @param UserContextInterface|null $userContext
   * @return OzonCatalogProductModel[]|ModelCollection
   * @throws ArgumentException
   * @throws ObjectPropertyException
   * @throws SystemException
   */
  public function getInternalList(array $params, ?UserContextInterface $userContext = null): ModelCollection
  {
    $params['select'] = $params['select'] ?? [
      "ID",
      "NAME",
      "ACTIVE",
      "IBLOCK_ID",
      "DATE_CREATE",
      "ACTIVE_FROM",
      "ACTIVE_TO",
      "SORT",
      "PREVIEW_PICTURE",
      "PREVIEW_TEXT",
      "DETAIL_PICTURE",
      "DETAIL_TEXT",
      "CODE",
      "TAGS",
      "IBLOCK_SECTION_ID",
      "IBLOCK_SECTION",
      "TIMESTAMP_X",
      "BRAND_VALUE" => "BRAND.VALUE",
      "PARTNUMBER_VALUE" => "PARTNUMBER.VALUE",
      "MARKA_TS_VALUE" => "MARKA_TS.VALUE",
      "MODELTS_VALUE" => "MODELTS.VALUE",
      "TYPE_VALUE" => "TYPE.VALUE",
      "ARTIKUL_VALUE" => "ARTIKUL.VALUE",
      "STRANA_IZGOTOVITEL_VALUE" => "STRANA_IZGOTOVITEL.VALUE",
      "GARANTY_TERM_VALUE" => "GARANTY_TERM.VALUE",
      "TECHNIQUE_TYPE_VALUE" => "TECHNIQUE_TYPE.VALUE",
      "AMOUNT_PACKAGE_VALUE" => "AMOUNT_PACKAGE.VALUE",
      "EQUIPMENT_VALUE" => "EQUIPMENT.VALUE",
      "ALTERNATE_ARTIKUL_VALUE" => "ALTERNATE_ARTIKUL.VALUE",
      "AMOUNT_VALUE" => "AMOUNT.VALUE",
      "CARVING_VALUE" => "CARVING.VALUE",
      "PRODUCT_ID_VALUE" => "PRODUCT_ID.VALUE",
      "OFFER_ID_VALUE" => "OFFER_ID.VALUE",
      "IMAGES_LIST_VALUE" => "IMAGES_LIST.VALUE",
      "DISCOUNT_VALUE" => "DISCOUNT.VALUE",
      "HEIGHT_VALUE" => "HEIGHT.VALUE",
      "WIDTH_VALUE" => "WIDTH.VALUE",
      "DEPTH_VALUE" => "DEPTH.VALUE",
      "DIMENSION_UNIT_VALUE" => "DIMENSION_UNIT.VALUE",
      "WEIGHT_VALUE" => "WEIGHT.VALUE",
      "WEIGHT_UNIT_VALUE" => "WEIGHT_UNIT.VALUE",
      "CURRENCY_CODE_VALUE" => "CURRENCY_CODE.VALUE",
      "PRICE_VALUE" => "PRICE.VALUE",
      "MARKETING_PRICE_VALUE" => "MARKETING_PRICE.VALUE",
      "OLD_PRICE_VALUE" => "OLD_PRICE.VALUE",
      "MIN_PRICE_VALUE" => "MIN_PRICE.VALUE",
     ];
    $list = ElementCatalogozonTable::getList($params);

    return new ModelCollection($list, OzonCatalogProductModel::class);
  }


  /**
   * @param int $id
   * @param UserContextInterface|null $userContext
   * @return OzonCatalogProductModel|AbsOptimizedModel|null
   * @throws ArgumentException
   * @throws ObjectPropertyException
   * @throws SystemException
   */
  public function getById(int $id, ?UserContextInterface $userContext = null): ?AbsOptimizedModel
  {
    $params = [
     'filter' => [
      '=id' => $id,
     ],
    ];
    $collection = $this->getList($params, $userContext);

    return $collection->first();
  }


  /**
   * @param array $params
   * @param UserContextInterface|null $userContext
   * @return int
   * @throws ArgumentException
   * @throws ObjectPropertyException
   * @throws SystemException
   */
  public function getCount(array $params, ?UserContextInterface $userContext = null): int
  {
    $params['count_total'] = true;
    return ElementCatalogozonTable::getList($params)->getCount();
  }


  /**
   * @param int $id
   * @param UserContextInterface|null $userContext
   * @return Result
   * @throws ArgumentException
   * @throws ObjectPropertyException
   * @throws SystemException
   * @throws Exception
   */
  public function delete(int $id, ?UserContextInterface $userContext = null): Result
  {
    $item = $this->getById($id, $userContext);
    if (!($item instanceof OzonCatalogProductModel)) {
      return (new Result)->addError(new Error('Не найдена запись для удаления'));
    }

    return ElementCatalogozonTable::delete($id);
  }


  /**
   * @param OzonCatalogProductModel $model
   * @param UserContextInterface|null $userContext
   * @return Result
   * @throws Exception
   */
  public function save(AbsOptimizedModel $model, ?UserContextInterface $userContext = null): Result
  {
      $element = new \Bitrix\Iblock\Elements\EO_ElementCatalogozon();
      if ($model->getId() > 0) {
        $element->setId($model->getId());
        $element->sysChangeState(State::CHANGED);
      }

      if($model->hasValueKey('NAME')) {
        $element->set('NAME', $model->getName());
      }

      if($model->hasValueKey('ACTIVE')) {
        $element->set('ACTIVE', $model->getActive());
      }

      if($model->hasValueKey('DATE_CREATE')) {
        $element->set('DATE_CREATE', $model->getDateCreate());
      }

      if($model->hasValueKey('ACTIVE_FROM')) {
        $element->set('ACTIVE_FROM', $model->getActiveFrom());
      }

      if($model->hasValueKey('ACTIVE_TO')) {
        $element->set('ACTIVE_TO', $model->getActiveTo());
      }

      if($model->hasValueKey('SORT')) {
        $element->set('SORT', $model->getSort());
      }

      if($model->hasValueKey('PREVIEW_PICTURE')) {
        $element->set('PREVIEW_PICTURE', $model->getPreviewPicture());
      }

      if($model->hasValueKey('PREVIEW_TEXT')) {
        $element->set('PREVIEW_TEXT', $model->getPreviewText());
      }

      if($model->hasValueKey('DETAIL_PICTURE')) {
        $element->set('DETAIL_PICTURE', $model->getDetailPicture());
      }

      if($model->hasValueKey('DETAIL_TEXT')) {
        $element->set('DETAIL_TEXT', $model->getDetailText());
      }

      if($model->hasValueKey('CODE')) {
        $element->set('CODE', $model->getCode());
      }

      if($model->hasValueKey('TAGS')) {
        $element->set('TAGS', $model->getTags());
      }

      if($model->hasValueKey('IBLOCK_SECTION_ID')) {
        $element->set('IBLOCK_SECTION_ID', $model->getIblockSectionId());
      }

      if($model->hasValueKey('IBLOCK_SECTION')) {
        $element->set('IBLOCK_SECTION', $model->getIblockSection());
      }

      if($model->hasValueKey('BRAND_VALUE')) {
        $element->set('BRAND', $model->getBrand());
      }

      if($model->hasValueKey('PARTNUMBER_VALUE')) {
        $element->set('PARTNUMBER', $model->getPartnumber());
      }

      if($model->hasValueKey('MARKA_TS_VALUE')) {
        $element->set('MARKA_TS', $model->getMarkaTs());
      }

      if($model->hasValueKey('MODELTS_VALUE')) {
        $element->set('MODELTS', $model->getModelts());
      }

      if($model->hasValueKey('TYPE_VALUE')) {
        $element->set('TYPE', $model->getType());
      }

      if($model->hasValueKey('ARTIKUL_VALUE')) {
        $element->set('ARTIKUL', $model->getArtikul());
      }

      if($model->hasValueKey('STRANA_IZGOTOVITEL_VALUE')) {
        $element->set('STRANA_IZGOTOVITEL', $model->getStranaIzgotovitel());
      }

      if($model->hasValueKey('GARANTY_TERM_VALUE')) {
        $element->set('GARANTY_TERM', $model->getGarantyTerm());
      }

      if($model->hasValueKey('TECHNIQUE_TYPE_VALUE')) {
        $element->set('TECHNIQUE_TYPE', $model->getTechniqueType());
      }

      if($model->hasValueKey('AMOUNT_PACKAGE_VALUE')) {
        $element->set('AMOUNT_PACKAGE', $model->getAmountPackage());
      }

      if($model->hasValueKey('EQUIPMENT_VALUE')) {
        $element->set('EQUIPMENT', $model->getEquipment());
      }

      if($model->hasValueKey('ALTERNATE_ARTIKUL_VALUE')) {
        $element->set('ALTERNATE_ARTIKUL', $model->getAlternateArtikul());
      }

      if($model->hasValueKey('AMOUNT_VALUE')) {
        $element->set('AMOUNT', $model->getAmount());
      }

      if($model->hasValueKey('CARVING_VALUE')) {
        $element->set('CARVING', $model->getCarving());
      }

      if($model->hasValueKey('PRODUCT_ID_VALUE')) {
        $element->set('PRODUCT_ID', $model->getProductId());
      }

      if($model->hasValueKey('OFFER_ID_VALUE')) {
        $element->set('OFFER_ID', $model->getOfferId());
      }

      if($model->hasValueKey('IMAGES_LIST_VALUE')) {
        $element->set('IMAGES_LIST', $model->getImagesList());
      }

      if($model->hasValueKey('DISCOUNT_VALUE')) {
        $element->set('DISCOUNT', $model->getDiscount());
      }

      if($model->hasValueKey('HEIGHT_VALUE')) {
        $element->set('HEIGHT', $model->getHeight());
      }

      if($model->hasValueKey('WIDTH_VALUE')) {
        $element->set('WIDTH', $model->getWidth());
      }

      if($model->hasValueKey('DEPTH_VALUE')) {
        $element->set('DEPTH', $model->getDepth());
      }

      if($model->hasValueKey('DIMENSION_UNIT_VALUE')) {
        $element->set('DIMENSION_UNIT', $model->getDimensionUnit());
      }

      if($model->hasValueKey('WEIGHT_VALUE')) {
        $element->set('WEIGHT', $model->getWeight());
      }

      if($model->hasValueKey('WEIGHT_UNIT_VALUE')) {
        $element->set('WEIGHT_UNIT', $model->getWeightUnit());
      }

      if($model->hasValueKey('CURRENCY_CODE_VALUE')) {
        $element->set('CURRENCY_CODE', $model->getCurrencyCode());
      }

      if($model->hasValueKey('PRICE_VALUE')) {
        $element->set('PRICE', $model->getPrice());
      }

      if($model->hasValueKey('MARKETING_PRICE_VALUE')) {
        $element->set('MARKETING_PRICE', $model->getMarketingPrice());
      }

      if($model->hasValueKey('OLD_PRICE_VALUE')) {
        $element->set('OLD_PRICE', $model->getOldPrice());
      }

      if($model->hasValueKey('MIN_PRICE_VALUE')) {
        $element->set('MIN_PRICE', $model->getMinPrice());
      }

      $oElementOld = new CIBlockElement();
      $sectionId = $model->getIblockSectionId();
      if ($model->getId() > 0) {
        $result = $element->save();
        $oElementOld->Update($result->getPrimary()['ID'], ['IBLOCK_SECTION_ID' => $sectionId]);
        return $result;
      }

      $result = $element->save();
      if ($result->isSuccess()) {
        $oElementOld->Update($result->getPrimary()['ID'], ['IBLOCK_SECTION_ID' => $sectionId]);
        $model->setId($result->getId());
      }

      return $result;
  }


  /**
   * @return array
   */
  public static function getSortFields(): array
  {
    return [
     "id" => "ID",
     "name" => "NAME",
     "active" => "ACTIVE",
     "iblock_id" => "IBLOCK_ID",
     "date_create" => "DATE_CREATE",
     "active_from" => "ACTIVE_FROM",
     "active_to" => "ACTIVE_TO",
     "sort" => "SORT",
     "preview_picture" => "PREVIEW_PICTURE",
     "preview_text" => "PREVIEW_TEXT",
     "detail_picture" => "DETAIL_PICTURE",
     "detail_text" => "DETAIL_TEXT",
     "code" => "CODE",
     "tags" => "TAGS",
     "iblock_section_id" => "IBLOCK_SECTION_ID",
     "iblock_section" => "IBLOCK_SECTION",
     "timestamp_x" => "TIMESTAMP_X",
     "brand" => "BRAND.VALUE",
     "partnumber" => "PARTNUMBER.VALUE",
     "marka_ts" => "MARKA_TS.VALUE",
     "modelts" => "MODELTS.VALUE",
     "type" => "TYPE.VALUE",
     "artikul" => "ARTIKUL.VALUE",
     "strana_izgotovitel" => "STRANA_IZGOTOVITEL.VALUE",
     "garanty_term" => "GARANTY_TERM.VALUE",
     "technique_type" => "TECHNIQUE_TYPE.VALUE",
     "amount_package" => "AMOUNT_PACKAGE.VALUE",
     "equipment" => "EQUIPMENT.VALUE",
     "alternate_artikul" => "ALTERNATE_ARTIKUL.VALUE",
     "amount" => "AMOUNT.VALUE",
     "carving" => "CARVING.VALUE",
     "product_id" => "PRODUCT_ID.VALUE",
     "offer_id" => "OFFER_ID.VALUE",
     "images_list" => "IMAGES_LIST.VALUE",
     "discount" => "DISCOUNT.VALUE",
     "height" => "HEIGHT.VALUE",
     "width" => "WIDTH.VALUE",
     "depth" => "DEPTH.VALUE",
     "dimension_unit" => "DIMENSION_UNIT.VALUE",
     "weight" => "WEIGHT.VALUE",
     "weight_unit" => "WEIGHT_UNIT.VALUE",
     "currency_code" => "CURRENCY_CODE.VALUE",
     "price" => "PRICE.VALUE",
     "marketing_price" => "MARKETING_PRICE.VALUE",
     "old_price" => "OLD_PRICE.VALUE",
     "min_price" => "MIN_PRICE.VALUE",
    ];
  }


  /**
   * @return array
   */
  public static function getFilterFields(): array
  {
    return [
     "id" => "ID",
     "name" => "NAME",
     "active" => "ACTIVE",
     "iblock_id" => "IBLOCK_ID",
     "date_create" => "DATE_CREATE",
     "active_from" => "ACTIVE_FROM",
     "active_to" => "ACTIVE_TO",
     "sort" => "SORT",
     "preview_picture" => "PREVIEW_PICTURE",
     "preview_text" => "PREVIEW_TEXT",
     "detail_picture" => "DETAIL_PICTURE",
     "detail_text" => "DETAIL_TEXT",
     "code" => "CODE",
     "tags" => "TAGS",
     "iblock_section_id" => "IBLOCK_SECTION_ID",
     "iblock_section" => "IBLOCK_SECTION",
     "timestamp_x" => "TIMESTAMP_X",
     "brand" => "BRAND.VALUE",
     "partnumber" => "PARTNUMBER.VALUE",
     "marka_ts" => "MARKA_TS.VALUE",
     "modelts" => "MODELTS.VALUE",
     "type" => "TYPE.VALUE",
     "artikul" => "ARTIKUL.VALUE",
     "strana_izgotovitel" => "STRANA_IZGOTOVITEL.VALUE",
     "garanty_term" => "GARANTY_TERM.VALUE",
     "technique_type" => "TECHNIQUE_TYPE.VALUE",
     "amount_package" => "AMOUNT_PACKAGE.VALUE",
     "equipment" => "EQUIPMENT.VALUE",
     "alternate_artikul" => "ALTERNATE_ARTIKUL.VALUE",
     "amount" => "AMOUNT.VALUE",
     "carving" => "CARVING.VALUE",
     "product_id" => "PRODUCT_ID.VALUE",
     "offer_id" => "OFFER_ID.VALUE",
     "images_list" => "IMAGES_LIST.VALUE",
     "discount" => "DISCOUNT.VALUE",
     "height" => "HEIGHT.VALUE",
     "width" => "WIDTH.VALUE",
     "depth" => "DEPTH.VALUE",
     "dimension_unit" => "DIMENSION_UNIT.VALUE",
     "weight" => "WEIGHT.VALUE",
     "weight_unit" => "WEIGHT_UNIT.VALUE",
     "currency_code" => "CURRENCY_CODE.VALUE",
     "price" => "PRICE.VALUE",
     "marketing_price" => "MARKETING_PRICE.VALUE",
     "old_price" => "OLD_PRICE.VALUE",
     "min_price" => "MIN_PRICE.VALUE",
    ];
  }
}
