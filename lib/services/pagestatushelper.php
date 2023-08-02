<?php

namespace Loodsen\Ozon\Services;

class PageStatusHelper
{
  public static function setStatus404()
  {
    if (!defined("ERROR_404"))
      define("ERROR_404", "Y");
    \CHTTP::setStatus("404 Not Found");
    global $APPLICATION;
    if ($APPLICATION->RestartWorkarea())
    {
      require(\Bitrix\Main\Application::getDocumentRoot() . "/404.php");
      die();
    }
  }
}
