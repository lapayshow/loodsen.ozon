<?php

namespace Loodsen\Ozon\Controllers;

use COption;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Request;
use Loodsen\Ozon\Services\OzonApiClientService;
use Loodsen\Ozon\References\ApiMethodsList;
use Loodsen\Ozon\References\ConfigList;
use Loodsen\Ozon\Services\OzonImportService;

class OzonApiController extends Controller
{
  protected $ozonApiClientService;
  protected $ozonApiMaxGetLimit;
  protected $ozonImportService;
  protected $categoryIdList;

  public function __construct(Request $request = null)
  {
    parent::__construct($request);

    $this->ozonApiClientService = new OzonApiClientService();
    $this->ozonApiMaxGetLimit = ConfigList::OZON_API_MAX_GET_LIMIT;
    $this->ozonImportService = new OzonImportService();
  }

  public function testImportAction(): string
  {
    $categoryTree = $this->getCategoryTree();
    $countCategories = $this->getCountCategories($categoryTree) ?? 0;
    $arProducts = $this->getFullProductList();

    $productIdList = [];
    foreach ($arProducts as $value) {
      $productIdList[] = $value['product_id'];
    }

    $arProductsAttributes = $this->getProductAttributes(productIdList: $productIdList);

    if ($countCategories >= 0 && !empty($arProducts)) {
      return "Тест импорта выполнен успешно" . "<br>" .
       "Доступно категорий для сохранения: " . $countCategories . "<br>" .
       "Доступно товаров для сохранения: " . count($arProductsAttributes);
    }

    return 'Ошибка выполнения импорта';
  }

  public function getCategoriesAction(): ?array
  {
    $ozonImportService = $this->ozonImportService;

    $categoryTree = $this->getCategoryTree();

    $result = $ozonImportService->saveCategoryList($categoryTree);

    return $result;
  }

  public function getProductsAction(): ?array
  {
    $ozonImportService = $this->ozonImportService;
    $arProducts = $this->getFullProductList();

    $productIdList = [];
    foreach ($arProducts as $value) {
      $productIdList[] = $value['product_id'];
    }

    $productList = $this->getProductAttributes(productIdList: $productIdList);

    $result = $ozonImportService->saveProductList($productList);

    return $result;
  }

  // Возвращает дерево категорий товаров от указанного в настройках модуля
  public function getCategoryTree(): ?array
  {
    $parentCategoryId = COption::GetOptionString(ConfigList::MODULE_ID, 'PARENT_CATEGORY_ID');
    $parentTreeActive = COption::GetOptionString(ConfigList::MODULE_ID, 'PARENT_TREE_ACTIVE');

    if (!empty($parentCategoryId) && $parentTreeActive == "Y") {
      $post = [
       'category_id' => $parentCategoryId,
       'language' => 'DEFAULT',
      ];
    }
    $categoryTree = $this->ozonApiClientService->getData(ApiMethodsList::GET_CATEGORY_TREE, $post)['result'];

    return $categoryTree;
  }

  protected function getCountCategories($categoryTree): ?int
  {
    foreach ($categoryTree as $category) {
      if (!empty($category['category_id'])) {
        $this->categoryIdList[] = $category['category_id'];
      }
      if (!empty($category['children'])) {
        $this->getCountCategories($category['children']);
      }
    }
    return count(array_unique($this->categoryIdList));
  }

  // Возвращает массив всех товаров без фильтра
  public function getFullProductList (array $post = null): ?array
  {
    if (empty($post['last_id'])) {
      $post = [
       'last_id' => '',
       'limit' => $this->ozonApiMaxGetLimit,
      ];
    }

    $result = $this->ozonApiClientService->getData(ApiMethodsList::GET_PRODUCT_LIST, $post)['result'];
    $res = $result['items'];
    if (!empty($result['last_id'])) {
      $post = [
       'last_id' => $result['last_id'],
       'limit' => $this->ozonApiMaxGetLimit,
      ];
      $res = array_merge($res, $this->getFullProductList($post));
    }

    return $res;
  }

  // Возвращает список всех идентификаторов товаров по фильтру в настройках
  public function getProductAttributes(array $productIdList = []): ?array
  {
    $filterSpecialParam = COption::GetOptionString(
     ConfigList::MODULE_ID,
     'FILTER_SPECIAL_PARAM'
    );

    if (count($productIdList) > $this->ozonApiMaxGetLimit) {
      $productIdListChunk = array_chunk(
       $productIdList,
       $this->ozonApiMaxGetLimit
      );
    }
    $filterSpecialParam = explode('|', $filterSpecialParam);
    $brandAttributeId = $filterSpecialParam[0];
    $brandAttributeValue = $filterSpecialParam[1];

    $productsList = [];
    foreach ($productIdListChunk as $chunk) {
      $post = [
       'product_id' => $chunk,
      ];

      $productInfo = $this->ozonApiClientService->getData(
       ApiMethodsList::GET_PRODUCT_INFO_LIST,
       $post
      )['result'];

      $post = [
       'filter' => [
        'product_id' => $chunk,
        'visibility' => 'VISIBLE'
       ],
       'limit'  => $this->ozonApiMaxGetLimit,
      ];

      // TODO: этот запрос делать уже после фильтрации, а не на всё количество
      $productAttributes = $this->ozonApiClientService->getData(
       ApiMethodsList::GET_PRODUCT_INFO_ATTRIBUTES,
       $post
      )['result'];

      $post = [
       'filter' => [
        'product_id' => $chunk,
        'visibility' => 'VISIBLE'
       ],
       'limit'  => $this->ozonApiMaxGetLimit,
      ];

      // Получаем инфу об остатках на складах
      $productStocksInfo = $this->ozonApiClientService->getData(
       ApiMethodsList::GET_PRODUCT_INFO_STOCKS,
       $post
      )['result'];

      // Фильтр по атрибуту
      if (!empty($brandAttributeId) && !empty($brandAttributeValue)) {
        foreach ($productAttributes as $key => $product) {
          foreach ($product['attributes'] as $attribute) {
            if ($attribute['attribute_id'] == $brandAttributeId
             && current(
                 $attribute['values']
                )['value'] == $brandAttributeValue
            ) {
              // TODO: проверить на ошибки и число итераций
              foreach ($productInfo['items'] as $info) {
                if ($info['id'] == $product['id']) {
                  $productAttributes[$key]['product_info'] = $info;
                }
              }
              foreach ($productStocksInfo['items'] as $stocksInfo) {
                if ($stocksInfo['product_id'] == $product['id']) {
                  $productAttributes[$key]['product_stocks_info'] = $stocksInfo;
                }
              }
              $productsList[] = $productAttributes[$key];
            }
          }
        }
      } else {
        $productsList = array_merge($productsList, $productAttributes);
      }
    }

    return $productsList;
  }
}
