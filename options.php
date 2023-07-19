<?php
global $USER;
global $APPLICATION;

if(!$USER->IsAdmin()) {
  return;
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true || die();

$moduleId = 'loodsen.ozon';

Loc::loadMessages(__FILE__);
Loader::includeModule($moduleId);

$options = [
 [
  'tab'     => "Общие настройки",
  'options' => [
   'CATALOG_OZON_IBLOCK_CODE' => [
        'label' => 'Символьный код инфоблока',
        'name' => 'CATALOG_OZON_IBLOCK_CODE',
        'type' => 'text',
        'tooltip' => 'Необходимо указать ID инфоблока, куда будут сохранены категории в виде разделов и товары в виде элементов инфоблока',
   ],
   'PARENT_CATEGORY_ID' => [
        'label' => 'Родительская категория (category_id)',
        'name' => 'PARENT_CATEGORY_ID',
        'type' => 'text',
        'tooltip' => 'Указывается category_id с сайта Ozon.ru, если известен',
   ],
   'PARENT_TREE_ACTIVE'=> [
        'label' => 'Построить дерево разделов по category_id',
        'name' => 'PARENT_TREE_ACTIVE',
        'type' => 'checkbox',
        'tooltip' => 'Если указан category_id, будет построено дерево разделов',
   ],
   'FILTER_SPECIAL_PARAM'=> [
        'label' => 'Особое условие фильтрации товаров',
        'name' => 'FILTER_SPECIAL_PARAM',
        'type' => 'text',
        'tooltip' => 'Характеристики товара в формате: attribute_id|attribute_value',
   ],
  ],
 ],
 [
  'tab'     => "Настройки интеграции API",
  'options' => [
   'OZON_CLIENT_ID' => [
        'label' => 'Ozon.ru CLIENT ID',
        'name' => 'OZON_CLIENT_ID',
        'type' => 'text',
        'tooltip' => 'Указывается уникальный Client-Id из личного кабинета на Ozon.ru',
   ],
   'OZON_API_KEY' => [
    'label' => 'Ozon.ru API KEY',
    'name' => 'OZON_API_KEY',
    'type' => 'text',
    'tooltip' => 'Указывается уникальный Api-Key из личного кабинета на Ozon.ru',
   ],
  ],
 ],
];

$optionsRng = [];
$optionJson = [];
$optionNames = [];
foreach ($options as $optionTab) {
  foreach ($optionTab['options'] as $name => $value) {
    if (is_string($value)) {
      $optionNames[] = $name;
    }
    else if (is_array($value)) {
      $name = $value['name'] ?? null;
      if ($name) {
        $optionNames[] = $name;

        $isRng = ($value['type'] ?? '') === 'rng';
        $multiple = (bool) ($value['multiple'] ?? false);
        if ($isRng) {
          $optionsRng[] = $name;
        }

        if ($multiple) {
          $optionJson[] = $name;
        }
      }
    }
  }
}

$isSave = $_POST['save'] ?? $_POST['apply'] ?? false;
if ($isSave && check_bitrix_sessid()) {
  foreach ($optionNames as $name) {
    $value = $_POST[$name] ?? null;
    if (is_array($value)) {
      $value = array_filter($value);
    }

    $isMultiple = in_array($name, $optionJson);
    if (in_array($name, $optionsRng)) {
      $newValue = [];
      if ($isMultiple) {
        $fromValues = (array)($value['from'] ?? []);
        $toValues = (array)($value['to'] ?? []);
        foreach($fromValues as $i => $fromValue) {
          $newValue[] = [
           'from' => $fromValue,
           'to' => $toValues[$i] ?? '',
          ];
        }
      } else {
        $newValue['from'] = $value['from'] ?? '';
        $newValue['to'] = $value['to'] ?? '';
      }

      $value = json_encode($newValue);
    } elseif (in_array($name, $optionJson)) {
      $value = json_encode($value);
    }

    Option::set($moduleId, $name, "{$value}");
  }
}

$aTabs = array_map(
 function ($item) {
   static $i = 0;
   return [
    'ICON' => '',
    'DIV' => 'tab'.($i++),
    'TAB' => $item['tab'],
    'TITLE' => $item['tab'],
   ];
 }, $options
);

$tabControl = new CAdminTabControl('tabControl', $aTabs);
$actionUrl = $APPLICATION->GetCurPage() ."?mid=".urlencode($moduleId)."&lang=".LANGUAGE_ID;

?>
<form method="post" action="<?php echo $actionUrl ?>">
  <?php
  echo bitrix_sessid_post();

  $tabControl->Begin();
  foreach ($options as $optionTab) {
    $tabControl->BeginNextTab();
    foreach ($optionTab['options'] as $name => $value) {
      if (is_string($value)) {
        unset($optionTooltip);
        $optionName = $name;
        $optionLabel = $value;
        $optionType = "text";
      }
      else if (is_array($value)) {
        unset($optionTooltip);
        $optionGroup = $value['group'] ?? null;
        if ($optionGroup) {
          echo "<tr class='heading'><td colspan='2'>{$optionGroup}</td></tr>";
          continue;
        }

        $optionType = $value['type'] ?? 'text';

        $optionName = $value['name'] ?? null;
        if (!$optionName) {
          continue;
        }

        $optionLabel = $value['label'] ?? $optionName;
        if (array_key_exists('tooltip', $optionTab['options'][$name])) {
          $optionTooltip = $value['tooltip'];
        }
      }

      $optionValue = (string)Option::get($moduleId, $optionName);

      $decodedValue = json_decode($optionValue, true) ?? null;
      if ($decodedValue) {
        $optionValue = $decodedValue;
      }

      ?>
      <tr>
        <td class="adm-detail-content-cell-l">
          <?php echo $optionLabel ?>
        </td>
        <td class="adm-detail-content-cell-r">
          <?php
          switch ($optionType) {
            case 'select':
              $selectValues = $value['values'];
              $isAssocSelectValues = !empty(
              array_diff_assoc(
               array_keys($selectValues),
               range(0, count($selectValues)-1)
              )
              );

              $multiple = (bool) ($value['multiple'] ?? false);
              $size = 1;
              if ($multiple) {
                $size = 5;
                $optionName .= "[]";
              }

              echo "<select class='typeselect' name='{$optionName}' size='{$size}' ".($multiple ? 'multiple' : '').">";
              foreach ($selectValues as $key => $item) {
                if ($isAssocSelectValues) {
                  $selectOptionValue = $key;
                }
                else {
                  $selectOptionValue = $item;
                }

                if ($multiple) {
                  $selected = in_array($selectOptionValue, $optionValue) ? "selected" : "";
                }
                else {
                  $selected = "{$selectOptionValue}" === "{$optionValue}" ? "selected" : "";
                }

                echo "<option value='{$selectOptionValue}' {$selected}>{$item}</option>";
              }
              echo "</select>";
              break;

            case 'checkbox':
              $optionValue = $optionValue ?: 'N';
              $checked = $optionValue == 'Y' ? "checked" : "";
              echo "
                            <input class='adm-designed-checkbox' type='checkbox' id='{$optionName}' name='{$optionName}' value='Y' {$checked}>
                            <label class='adm-designed-checkbox-label' for='{$optionName}'></label>
                            ";
              break;

            case 'rng':
              $multiple = (bool) ($value['multiple'] ?? false);
              if ($multiple) {
                //$optionName .= "[]";
                foreach ((array)$optionValue as $item) {
                  if (empty($item)) {
                    continue;
                  }
                  echo "<div><input name='{$optionName}[from][]' value='{$item['from']}'><input name='{$optionName}[to][]' value='{$item['to']}'></div>";
                }
                echo "<div>
                  <input type='button' value='Добавить' onclick='addTemplateRow(this, {});'>
                  <div class='jsTemplateRow' style='display:none;'>
                                              <input name='{$optionName}[from][]' value=''>
                                              <input name='{$optionName}[to][]' value=''>
                  </div>
                  </div>";
              }
              else {
                echo "<div><input name='{$optionName}[from]' value='{$optionValue['from']}'><input name='{$optionName}[to]' value='{$optionValue['to']}'></div>";
              }
              break;

            default:
              $multiple = (bool) ($value['multiple'] ?? false);
              if ($multiple) {
                $optionName .= "[]";
                foreach ((array)$optionValue as $item) {
                  if (empty($item)) {
                    continue;
                  }
                  echo "<div><input type='{$optionType}' name='{$optionName}' value='{$item}'></div>";
                }
                echo "<div>
                                    <input type='button' value='Добавить' onclick='addTemplateRow(this);'>
                                    <div class='jsTemplateRow' style='display:none;'>
                                        <input type='{$optionType}' name='{$optionName}' value=''>
                                    </div>
                                    </div>";
              }
              else {
                echo "<input type='{$optionType}' name='{$optionName}' value='{$optionValue}'>";
              }
              break;
          }
          ?>
            <div class="tooltip_options" data-tooltip="<?=$optionTooltip;?>">
              <?if($optionTooltip):?>
                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                         width="16px" height="16px" viewBox="0 0 1280.000000 1280.000000"
                         preserveAspectRatio="xMidYMid meet" class="svg_ask_icon">
                        <metadata>
                            Created by potrace 1.15, written by Peter Selinger 2001-2017
                        </metadata>
                        <g transform="translate(0.000000,1280.000000) scale(0.100000,-0.100000)"
                           fill="#000000" stroke="none">
                            <path d="M6095 12794 c-1453 -77 -2788 -613 -3885 -1558 -138 -119 -447 -423
                                -570 -561 -517 -579 -915 -1221 -1194 -1928 -224 -567 -361 -1145 -423 -1792
                                -24 -252 -24 -858 0 -1110 108 -1120 456 -2103 1068 -3016 355 -531 836 -1049
                                1344 -1450 989 -780 2125 -1232 3410 -1356 252 -24 858 -24 1110 0 1113 107
                                2103 456 3004 1059 338 227 614 452 919 753 264 259 421 437 632 717 728 964
                                1148 2057 1267 3293 24 252 24 858 0 1110 -137 1422 -679 2669 -1617 3720
                                -123 138 -432 442 -570 561 -1031 889 -2265 1413 -3625 1539 -162 15 -716 27
                                -870 19z m525 -2385 c615 -61 1156 -302 1535 -683 246 -247 428 -573 514 -921
                                44 -178 56 -297 56 -575 0 -346 -31 -567 -111 -806 -138 -412 -413 -725 -835
                                -948 -218 -115 -423 -185 -771 -261 l-216 -48 -4 -311 c-5 -337 -8 -357 -68
                                -476 -51 -101 -155 -187 -281 -231 -94 -34 -285 -34 -384 -1 -170 57 -271 167
                                -322 352 -16 59 -18 117 -18 625 0 501 2 566 17 615 37 119 105 148 548 239
                                310 64 467 118 633 217 331 199 516 500 546 888 26 342 -74 602 -328 857 -112
                                112 -179 161 -301 220 -168 81 -318 113 -545 113 -309 1 -529 -57 -735 -192
                                -112 -74 -296 -258 -447 -447 -138 -172 -284 -318 -347 -346 -58 -25 -202 -39
                                -286 -28 -94 13 -164 51 -246 133 -123 123 -162 238 -151 449 17 311 118 549
                                341 800 246 275 590 494 996 631 406 137 777 178 1210 135z m-255 -6980 c67
                                -13 178 -69 246 -123 68 -55 124 -126 178 -226 184 -342 4 -770 -379 -901 -99
                                -34 -258 -32 -361 3 -109 38 -194 91 -275 172 -127 127 -181 278 -171 475 8
                                176 66 299 198 426 170 162 345 216 564 174z"/>
                        </g>
                    </svg>
              <?endif;?>
            </div>
        </td>
      </tr>
      <?php
    }
  }

  $tabControl->Buttons([]);
  $tabControl->End();
  ?>
</form>
<style>
    .adm-detail-content-cell-r .adm-designed-checkbox-label {
        vertical-align: inherit !important;
    }
    .svg_ask_icon {
        cursor: pointer;
    }
    .tooltip_options {
        display: inline-block;
        margin-left: 5px;
    }
    [data-tooltip] {
        position: relative; /* Относительное позиционирование */
    }
    [data-tooltip]::after {
        content: attr(data-tooltip); /* Выводим текст */
        position: absolute; /* Абсолютное позиционирование */
        width: 300px; /* Ширина подсказки */
        right: 0; top: 0; /* Положение подсказки */
        background: #000000;
        color: #fff; /* Цвет текста */
        padding: 0.5em; /* Поля вокруг текста */
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); /* Параметры тени */
        pointer-events: none; /* Подсказка */
        opacity: 0; /* Подсказка невидима */
        transition: 1s; /* Время появления подсказки */
        border-radius: 5px;
        z-index: 1000;
    }
    [data-tooltip]:hover::after {
        opacity: 1; /* Показываем подсказку */
        top: 2em; /* Положение подсказки */
    }
</style>
<style media="screen">
    .adm-detail-content-cell-l {
        width: 50%;
    }
    .adm-detail-content-cell-r select {
        width: auto;
        max-width: 100%;
    }
    .adm-detail-content-cell-l,
    .adm-detail-content-cell-r {
        vertical-align: top;
    }
</style>

<script type="text/javascript">
    function addTemplateRow(btn) {
        var templateRow = btn.parentNode.querySelector('.jsTemplateRow')
        if (!templateRow) {
            return;
        }

        var targetElement = btn.parentNode.parentNode;
        if (!targetElement) {
            return;
        }

        var div = document.createElement('div')
        div.innerHTML = templateRow.innerHTML
        targetElement.insertBefore(
            div, targetElement.lastElementChild
        )
    }
</script>
