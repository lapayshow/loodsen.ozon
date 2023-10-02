<?php

namespace Loodsen\Ozon\References;

class ConfigList
{
  public const MODULE_ID = 'loodsen.ozon';

  // Корневой адрес API OZON
  public const OZON_API_BASE_URL = 'https://api-seller.ozon.ru';

  // Корневой адрес API OZON
  public const OZON_API_MAX_GET_LIMIT = 1000;

  // Атрибуты товара в формате: 'Код свойства в ИБ' => Код атрибута Ozon
  public const ATTRIBUTES = [
   'EQUIPMENT' => 4384, // Комплектация
   'TYPE' => 8229, // Тип
   'PARTNUMBER' => 7236, // Партномер (артикул производителя)
   'ARTIKUL' => 9024, // Артикул
   'TECHNIQUE_TYPE' => 7206, // Вид техники
   'DESCRIPTION' => 4191, // Описание
   'MARKA_TS' => 7204, // Марка ТС
   'MODELTS' => 7212, // Модель ТС
   'STRANA_IZGOTOVITEL' => 4389, // Страна-изготовитель
   'BRAND' => 85, // Бренд
   'GARANTY_TERM' => 4385, // Гарантийный срок
   'AMOUNT_PACKAGE' => 7335, // Количество в упаковке, шт
  ];
}
