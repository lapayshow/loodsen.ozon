<?php

use Loodsen\Ozon\Services\Cron\FullImportService;

$isCli = (php_sapi_name() == 'cli');

if ($isCli) {
  define('NO_AGENT_CHECK', true);
  define('DisableEventsCheck', true);
  define('NO_KEEP_STATISTIC', true);
  define('NO_AGENT_STATISTIC', true);
  define('NOT_CHECK_PERMISSIONS', true);
  define('STOP_STATISTICS', true);

  $_SERVER["DOCUMENT_ROOT"] = __DIR__ . '/../../../../../..';

  require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

  FullImportService::UpdateProductsOnly();
}
