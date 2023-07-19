<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true || die();

if (!Loader::includeModule("loodsen.ozon")) return;
Loc::loadMessages(__FILE__);

return [
 [
    "sort"        => 1,
    "section"     => "loodsen.ozon",
    "parent_menu" => "global_menu_loodsen_tools",
    "icon"        => "ozon_menu_icon",
    "page_icon"   => "ozon_page_icon",
    "text"        => "Ozon.ru API Integration",
    "title"       => "Ozon.ru API Integration",
    "url"         => "",
    "items_id"    => "loodsen.ozon",
    "more_url"    => [],
    "items"       => [
     [
      "text" => "Настройки",
      "icon" => "ozon_settings_icon",
      "url"  => "settings.php?mid=loodsen.ozon",
     ],
     [
      "text"      => "Импорт",
      'title'     => "Импорт",
      "icon"      => "ozon_import_icon",
      "page_icon" => "ozon_import_icon",
      "url"       => "ozon_import.php?lang=" . LANGUAGE_ID,
      "module_id" => "loodsen.ozon",
      "items_id" => "menu_clouds_bucket_",
      "items" => []
     ],
    ],
  ],
];