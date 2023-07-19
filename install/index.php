<?php
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class loodsen_ozon extends CModule
{
  private string $moduleDirAdmin = __DIR__ . '/admin';

  public function __construct()
  {
    if(file_exists(__DIR__."/version.php")) {

      $arModuleVersion = [];

      include_once(__DIR__."/version.php");

      $this->MODULE_ID            = str_replace("_", ".", get_class($this));
      $this->MODULE_VERSION       = $arModuleVersion["VERSION"];
      $this->MODULE_VERSION_DATE  = $arModuleVersion["VERSION_DATE"];
      $this->MODULE_NAME          = Loc::getMessage("loodsen_ozon_module_name");
      $this->MODULE_DESCRIPTION   = Loc::getMessage("loodsen_ozon_module_desc");
      $this->PARTNER_NAME         = Loc::getMessage("loodsen_ozon_module_partner");
      $this->PARTNER_URI          = Loc::getMessage("loodsen_ozon_module_partner_uri");

    }
  }

  function DoInstall()
  {
    global $APPLICATION;
    if(CheckVersion(ModuleManager::getVersion("main"), "14.00.00")) {
      $this->InstallFiles();
//      $this->InstallDB();
      ModuleManager::registerModule($this->MODULE_ID);
      CopyDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
      CopyDirFiles(__DIR__ . "/themes", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes", true, true);
    } else {
      $APPLICATION->ThrowException('Версия главного модуля ниже 14. Не поддерживается технология D7, необходимая модулю. Пожалуйста обновите систему.');
    }
  }

  function DoUninstall()
  {
    global $APPLICATION;
    $this->UnInstallFiles();
//    $this->UnInstallDB();
    ModuleManager::unRegisterModule($this->MODULE_ID);
    DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
    DeleteDirFilesEx('/bitrix/themes/.default/icons/'.$this->MODULE_ID.'/');
    DeleteDirFiles($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.$this->MODULE_ID.'/install/themes/.default/', $_SERVER["DOCUMENT_ROOT"].'/bitrix/themes/.default/');
  }

  public function InstallDB(): bool
  {
    //    $connection = Application::getConnection();

    //      if(!$connection->isTableExists(FavoritesProductsUserTable::getTableName()))
    //        FavoritesProductsUserTable::getEntity()->createDbTable();

    return true;
  }


  public function UnInstallDB(): bool
  {
    //    $connection = Application::getConnection();

    //    if($connection->isTableExists(FavoritesProductsUserTable::getTableName()))
    //      $connection->dropTable(FavoritesProductsUserTable::getTableName());

    return true;
  }

  public function InstallFiles(): bool
  {
    $moduleDirAdmin = $this->moduleDirAdmin;

    if (file_exists($moduleDirAdmin)) {
      CopyDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
      CopyDirFiles(__DIR__ . "/themes", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes", true, true);
    }

    return true;
  }

  public function UnInstallFiles(): bool
  {
    $moduleDirAdmin = $this->moduleDirAdmin;

    if (file_exists($moduleDirAdmin)) {
      DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
      DeleteDirFilesEx('/bitrix/themes/.default/icons/'.$this->MODULE_ID.'/');
      DeleteDirFiles($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.$this->MODULE_ID.'/install/themes/.default/', $_SERVER["DOCUMENT_ROOT"].'/bitrix/themes/.default/');
    }

    return true;
  }

}
?>