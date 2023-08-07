<?php

namespace Loodsen\Ozon\References;

class ApiMethodsList
{
  // Дерево категории товаров
  public const GET_CATEGORY_TREE = '/v2/category/tree';

  // Список характеристик категории
  public const GET_CATEGORY_ATTRIBUTE = '/v3/category/attribute';

  // Справочник значений характеристики
  public const GET_CATEGORY_ATTRIBUTE_VALUES = '/v2/category/attribute/values';

  // Список товаров (получение идентификаторов)
  public const GET_PRODUCT_LIST = '/v2/product/list';

  // Информация о товарах (одиночное)
  public const GET_PRODUCT_INFO = '/v2/product/info';

  // Информация о товарах (множественное)
  public const GET_PRODUCT_INFO_LIST = '/v2/product/info/list';

  // Получить описание характеристик товара
  public const GET_PRODUCT_INFO_ATTRIBUTES = '/v3/products/info/attributes';

  // Получить описание товара
  public const GET_PRODUCT_INFO_DESCRIPTION = '/v1/product/info/description';

  // Информация о количестве товаров
  public const GET_PRODUCT_INFO_STOCKS = '/v3/product/info/stocks';

  // Получить информацию о цене товара
  public const GET_PRODUCT_INFO_PRICES = '/v4/product/info/prices';

  // Информация об остатках на складах продавца (FBS и rFBS)
  public const GET_PRODUCT_INFO_STOCK_BY_WAREHOUSE = '/v1/product/info/stocks-by-warehouse/fbs';
}
