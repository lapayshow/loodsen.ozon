<?php

namespace Loodsen\Ozon\Services\Cron;

use Loodsen\Ozon\Controllers\OzonApiController;

class FullImportService
{

  // Обновление категорий
  public static function FullImport(): ?bool
  {
    self::UpdateCategoriesOnly();
    self::UpdateProductsOnly();

    return null;
  }

  // Обновление категорий
  public static function UpdateCategoriesOnly(): ?bool
  {
    $ozonApiController = new OzonApiController;

    $categoriesResponse = $ozonApiController->getCategoriesAction();

    $dir = '../../../import_logs';
    if (!file_exists($dir)) {
      mkdir($dir, 0777, true);
    }

    $logDataProducts = date('Y-m-d H:i:s') . ' ' . print_r($categoriesResponse, true);

    $logFile = '../../../import_logs/import_catalogs_' . date('d-m-Y-H_i_s') . '.log';

    file_put_contents($logFile, $logDataProducts . PHP_EOL, FILE_APPEND);

    return null;
  }

  // Обновление товаров
  public static function UpdateProductsOnly(): ?bool
  {
    $ozonApiController = new OzonApiController;

    $productsResponse = $ozonApiController->getProductsAction();

    $dir = '../../../import_logs';
    if (!file_exists($dir)) {
      mkdir($dir, 0777, true);
    }

    $logDataProducts = date('Y-m-d H:i:s') . ' ' . print_r($productsResponse, true);

    $logFile = '../../../import_logs/import_products_' . date('d-m-Y-H_i_s') . '.log';

    file_put_contents($logFile, $logDataProducts . PHP_EOL, FILE_APPEND);

    return null;
  }
}

