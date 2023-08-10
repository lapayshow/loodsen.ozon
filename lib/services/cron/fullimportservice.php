<?php

namespace Loodsen\Ozon\Services\Cron;

use Loodsen\Ozon\Controllers\OzonApiController;

class FullImportService
{
  // Для полного импорта на cron
  public static function FullImport()
  {
    $ozonApiController = new OzonApiController;

    $categoriesResponse = $ozonApiController->getCategoriesAction();
    $productsResponse = $ozonApiController->getProductsAction();

    $dir = '../../../import_logs';
    if (!file_exists($dir)) {
      mkdir($dir, 0777, true);
    }

    $logDataCategory = date('Y-m-d H:i:s') . ' ' . print_r($categoriesResponse, true);
    $logDataProducts = date('Y-m-d H:i:s') . ' ' . print_r($productsResponse, true);

    $logFile = '../../../import_logs/full_import_' . date('d-m-Y-H_i_s');

    file_put_contents($logFile, $logDataCategory . PHP_EOL, FILE_APPEND);
    file_put_contents($logFile, $logDataProducts . PHP_EOL, FILE_APPEND);
  }

  // TODO: обновление только категорий
  public static function UpdateCategoriesOnly()
  {
    return false;
  }

  // TODO: обновление только ранее импортированных товаров
  public static function UpdateProductsOnly()
  {
    return false;
  }
}

