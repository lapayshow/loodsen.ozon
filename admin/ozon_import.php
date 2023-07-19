<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

global $APPLICATION;

$module_id = 'loodsen.ozon';

CJSCore::Init(array("jquery"));

$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($POST_RIGHT === 'D') {
  $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

if (!Loader::includeModule($module_id)) {
  $message = new CAdminMessage('Модуль' . $module_id . 'не подключен', 'Error include module');
}

$request = Application::getInstance()->getContext()->getRequest();

$tabControl = new CAdminTabControl("tabControl", [
 [
  "DIV" => "edit1",
  "TAB" => 'Импорт', "ICON" => "main_user_edit",
  "TITLE" => 'Импорт категорий с Ozon.ru'
 ],
]);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$context = new CAdminContextMenu([]);
$context->Show();

if (is_array($_SESSION["SESS_ADMIN"]["POSTING_EDIT_MESSAGE"])) {
  CAdminMessage::ShowMessage($_SESSION["SESS_ADMIN"]["POSTING_EDIT_MESSAGE"]);
  $_SESSION["SESS_ADMIN"]["POSTING_EDIT_MESSAGE"] = false;
}
Asset::getInstance()->addCss('/bitrix/css/main/bootstrap.min.css');

if ($message)
  echo $message->Show();
?>
<div id="edit1">
    <form id="form_ozon_import" method="POST" action="<? echo $APPLICATION->GetCurPage() ?>"
          ENCTYPE="multipart/form-data" name="ozon_import_categories">
        <?php $tabControl->Begin(); $tabControl->BeginNextTab(); ?>
            <div class="adm-detail-content-btns">

                <div class="import_block_test">
                    <div id="btn_import_test" class="btn-import-test">Протестировать импорт</div>
                    <div class="result_import_test"></div>
                </div>

                <div class="import_block_category">
                    <div id="btn_import_categories" class="btn-import">Импортировать категории</div>
                    <div class="result_import_category"></div>
                </div>

                <div class="import_block_products">
                    <div id="btn_import_products" class="btn-import">Импортировать товары</div>
                    <div class="result_import_products"></div>
                </div>

            </div>
        <?php $tabControl->End(); ?>
        <? echo bitrix_sessid_post(); ?>
        <input type="hidden" name="lang" value="<?= LANG ?>">
    </form>
</div>

<script>
    // Тест импорта
    $('#btn_import_test').on('click', function () {
        $('.result_import_test').html('Выполняется тестирование импорта...');
        BX.ajax.runAction('loodsen:ozon.OzonApiController.testImport', {
            data: {}
        }).then((response) => {
            $('.result_import_test').html(response['data']);
            // console.log(response);
        }, function (response) {
            // Ошибки импорта
            console.log(response);
        });
    });

    // Импорт категорий
    $('#btn_import_categories').on('click', function () {
        $('.result_import_category').html('Выполняется импорт категорий...');
        BX.ajax.runAction('loodsen:ozon.OzonApiController.getCategories', {
            data: {}
        }).then((response) => {
            $('.result_import_category').html(response['data']);
            // console.log(response);
        }, function (response) {
            // Ошибки импорта
            console.log(response);
        });
    });

    // Импорт товаров
    $('#btn_import_products').on('click', function () {
        $('.result_import_products').html('Выполняется импорт товаров...');
        BX.ajax.runAction('loodsen:ozon.OzonApiController.getProducts', {
            data: {}
        }).then((response) => {
            $('.result_import_products').html(response['data']);
            // console.log(response);
        }, function (response) {
            // Ошибки импорта
            console.log(response);
        });
    });
</script>

<style>
    .btn-import-test, .btn-import {
        font-weight: 500;
        font-size: 16px;
        line-height: 24px;
        text-align: center;
        text-decoration: none;
        padding: 12px 16px;
        border-radius: 8px;
        align-self: center;
        z-index: 2;
        transition: all ease 0.3s;
        cursor: pointer;
        margin: 15px 10px 0 0;
        width: 250px;
        display: block;
    }
    .btn-import-test {
        border: 2px solid #3B2B66;
        background: #3B2B66;
        color: #FFFFFF;
    }
    .btn-import-test:hover {
        background: #ffffff;
        color: #3B2B66;
        border: 2px solid #3B2B66;
    }
    .btn-import {
        border: 2px solid #e4764e;
        background: #e4764e;
        color: #FFFFFF;
    }
    .btn-import:hover {
        background: #ffffff;
        color: #e4764e;
        border: 2px solid #e4764e;
    }
    .result_import_test, .result_import_category, .result_import_products {
        margin-top: 10px;
        color: green;
        font-weight: 600;
        font-size: 14px;
    }
    .import_block_test, .import_block_category, .import_block_products {
        display: flex;
        justify-content: space-between;
    }
</style>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php"); ?>
