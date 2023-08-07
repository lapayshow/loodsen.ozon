<?php

namespace Loodsen\Ozon\Services\Catalog;

use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\Model\Section;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\Result;
use Bitrix\Main\SystemException;
use Bx\Model\AbsOptimizedModel;
use Bx\Model\BaseModelService;
use Bx\Model\Interfaces\UserContextInterface;
use Bx\Model\ModelCollection;
use CIBlockSection;
use Exception;
use Loodsen\Ozon\Models\Catalog\OzonCatalogSectionModel;
use COption;
use Loodsen\Ozon\References\ConfigList;

class OzonCatalogSectionService extends BaseModelService
{
  /**
   * @var int
   */
  private $iblockId;

  /**
   * @var DataManager
   */
  private $entity;


  /**
   * @throws LoaderException
   * @throws ArgumentException
   * @throws ObjectPropertyException
   * @throws SystemException
   */
  public function getIblockId(): int
  {
    if(!empty($this->iblockId)) {
      return (int)$this->iblockId;
    }

    $iblockCode = COption::GetOptionString(ConfigList::MODULE_ID, 'CATALOG_OZON_IBLOCK_CODE');

    Loader::includeModule('iblock');
    $iblockData = IblockTable::getRow([
     'filter' => [
      '=IBLOCK_TYPE_ID' => $iblockCode,
      '=CODE' => $iblockCode,
     ],
     'select' => [
      'ID'
     ],
    ]);

    return $this->iblockId = (int)$iblockData['ID'];
  }


  /**
   * @return SectionTable|DataManager|string|null
   * @throws ArgumentException
   * @throws LoaderException
   * @throws ObjectPropertyException
   * @throws SystemException
   */
  private function getEntityObjectMethod()
  {
    if ($this->entity instanceof DataManager) {
      return $this->entity;
    }

    Loader::includeModule('iblock');
    $iblockId = $this->getIblockId();
    return $this->entity = Section::compileEntityByIblock($iblockId);
  }


  /**
   * @param array $params
   * @param UserContextInterface|null $userContext
   * @return OzonCatalogSectionModel[]|ModelCollection
   * @throws ArgumentException
   * @throws ObjectPropertyException
   * @throws SystemException|LoaderException
   */
  public function getList(array $params, ?UserContextInterface $userContext = null): ModelCollection
  {
    $params['select'] = $params['select'] ?? [
      "ID",
      "NAME",
      "ACTIVE",
      "GLOBAL_ACTIVE",
      "IBLOCK_ID",
      "MODIFIED_BY",
      "CREATED_BY",
      "DATE_CREATE",
      "SORT",
      "PICTURE",
      "DETAIL_PICTURE",
      "SOCNET_GROUP_ID",
      "DESCRIPTION",
      "DESCRIPTION_TYPE",
      "SEARCHABLE_CONTENT",
      "CODE",
      "XML_ID",
      "TMP_ID",
      "IBLOCK_SECTION_ID",
      "LEFT_MARGIN",
      "RIGHT_MARGIN",
      "DEPTH_LEVEL",
      "TIMESTAMP_X",
      "UF_CATEGORY_ID_OZON",
     ];
    $params['filter']['=IBLOCK_ID'] = $this->getIblockId();
    $list = $this->getEntityObjectMethod()::getList($params);

    return new ModelCollection($list, OzonCatalogSectionModel::class);
  }


  /**
   * @param int $id
   * @param UserContextInterface|null $userContext
   * @return OzonCatalogSectionModel|AbsOptimizedModel|null
   * @throws ArgumentException
   * @throws ObjectPropertyException
   * @throws SystemException|LoaderException
   */
  public function getById(int $id, ?UserContextInterface $userContext = null): ?AbsOptimizedModel
  {
    $params = [
     'filter' => [
      '=id' => $id,
     ],
    ];
    $collection = $this->getList($params, $userContext);

    return $collection->first();
  }


  /**
   * @param string $xmlId
   * @param UserContextInterface|null $userContext
   * @return AbsOptimizedModel|null
   * @throws ArgumentException
   * @throws LoaderException
   * @throws ObjectPropertyException
   * @throws SystemException
   */
  public function getByXmlId(string $xmlId, ?UserContextInterface $userContext = null): ?AbsOptimizedModel
  {
    $params = [
     'filter' => [
      '=XML_ID' => $xmlId,
     ],
    ];
    $collection = $this->getList($params, $userContext);

    return $collection->first();
  }


  /**
   * @param array $params
   * @param UserContextInterface|null $userContext
   * @return int
   * @throws ArgumentException
   * @throws LoaderException
   * @throws ObjectPropertyException
   * @throws SystemException
   */
  public function getCount(array $params, ?UserContextInterface $userContext = null): int
  {
    $params['filter']['=IBLOCK_ID'] = $this->getIblockId();
    $params['count_total'] = true;
    return $this->getEntityObjectMethod()::getList($params)->getCount();
  }


  /**
   * @param int $id
   * @param UserContextInterface|null $userContext
   * @return Result
   * @throws ArgumentException
   * @throws ObjectPropertyException
   * @throws SystemException
   * @throws Exception
   */
  public function delete(int $id, ?UserContextInterface $userContext = null): Result
  {
    $item = $this->getById($id, $userContext);
    if (!($item instanceof OzonCatalogSectionModel)) {
      return (new Result)->addError(new Error('Не найдена запись для удаления'));
    }

    return $this->getEntityObjectMethod()::delete($id);
  }


  /**
   * @param OzonCatalogSectionModel $model
   * @param UserContextInterface|null $userContext
   * @return Result
   * @throws Exception
   */
  public function save(AbsOptimizedModel $model, ?UserContextInterface $userContext = null): Result
  {
    $result = new Result();
    $dataInfo = [
     'NAME' => [
      'value' => $model->getName(),
      'isFill' => $model->hasValueKey('NAME'),
     ],
     'ACTIVE' => [
      'value' => $model->getActive(),
      'isFill' => $model->hasValueKey('ACTIVE'),
     ],
     'SORT' => [
      'value' => $model->getSort(),
      'isFill' => $model->hasValueKey('SORT'),
     ],
     'PICTURE' => [
      'value' => $model->getPicture(),
      'isFill' => $model->hasValueKey('PICTURE'),
     ],
     'DETAIL_PICTURE' => [
      'value' => $model->getDetailPicture(),
      'isFill' => $model->hasValueKey('DETAIL_PICTURE'),
     ],
     'SOCNET_GROUP_ID' => [
      'value' => $model->getSocnetGroupId(),
      'isFill' => $model->hasValueKey('SOCNET_GROUP_ID'),
     ],
     'DESCRIPTION' => [
      'value' => $model->getDescription(),
      'isFill' => $model->hasValueKey('DESCRIPTION'),
     ],
     'DESCRIPTION_TYPE' => [
      'value' => $model->getDescriptionType(),
      'isFill' => $model->hasValueKey('DESCRIPTION_TYPE'),
     ],
     'CODE' => [
      'value' => $model->getCode(),
      'isFill' => $model->hasValueKey('CODE'),
     ],
     'XML_ID' => [
      'value' => $model->getXmlId(),
      'isFill' => $model->hasValueKey('XML_ID'),
     ],
     'IBLOCK_SECTION_ID' => [
      'value' => $model->getIblockSectionId(),
      'isFill' => $model->hasValueKey('IBLOCK_SECTION_ID'),
     ],
     'LEFT_MARGIN' => [
      'value' => $model->getLeftMargin(),
      'isFill' => $model->hasValueKey('LEFT_MARGIN'),
     ],
     'RIGHT_MARGIN' => [
      'value' => $model->getRightMargin(),
      'isFill' => $model->hasValueKey('RIGHT_MARGIN'),
     ],
     'DEPTH_LEVEL' => [
      'value' => $model->getDepthLevel(),
      'isFill' => $model->hasValueKey('DEPTH_LEVEL'),
     ],
     'UF_CATEGORY_ID_OZON' => [
      'value' => $model->getCategoryIdOzon(),
      'isFill' => $model->hasValueKey('UF_CATEGORY_ID_OZON'),
     ],
    ];

    $data = [
     'IBLOCK_ID' => $this->getIblockId(),
    ];

    foreach($dataInfo as $name => $info) {
      if ((bool)$info['isFill']) {
        $data[$name] = $info['value'];
      }
    }

    $oSection = new CIBlockSection();
    if ($model->getId() > 0) {
      $isSuccess = (bool)$oSection->Update($model->getId(), $data);
      if (!$isSuccess) {
        return $result->addError(new Error($oSection->LAST_ERROR));
      }
      return $result;
    }

    $id = (int)$oSection->Add($data);
    if ($id > 0) {
      $model->setId($id);
      return $result;
    }

    return $result->addError(new Error($oSection->LAST_ERROR));
  }


  public function getSectionActiveElementsCount($iblockSectionId): ?int
  {
    $activeElements = CIBlockSection::GetSectionElementsCount($iblockSectionId, Array("CNT_ACTIVE"=>"Y"));
    return $activeElements;
  }


  /**
   * @return array
   */
  public static function getSortFields(): array
  {
    return [
     "id" => "ID",
     "name" => "NAME",
     "active" => "ACTIVE",
     "global_active" => "GLOBAL_ACTIVE",
     "iblock_id" => "IBLOCK_ID",
     "modified_by" => "MODIFIED_BY",
     "created_by" => "CREATED_BY",
     "date_create" => "DATE_CREATE",
     "sort" => "SORT",
     "picture" => "PICTURE",
     "detail_picture" => "DETAIL_PICTURE",
     "socnet_group_id" => "SOCNET_GROUP_ID",
     "description" => "DESCRIPTION",
     "description_type" => "DESCRIPTION_TYPE",
     "searchable_content" => "SEARCHABLE_CONTENT",
     "code" => "CODE",
     "xml_id" => "XML_ID",
     "tmp_id" => "TMP_ID",
     "iblock_section_id" => "IBLOCK_SECTION_ID",
     "left_margin" => "LEFT_MARGIN",
     "right_margin" => "RIGHT_MARGIN",
     "depth_level" => "DEPTH_LEVEL",
     "timestamp_x" => "TIMESTAMP_X",
     "category_id_ozon" => "UF_CATEGORY_ID_OZON",
    ];
  }


  /**
   * @return array
   */
  public static function getFilterFields(): array
  {
    return [
     "id" => "ID",
     "name" => "NAME",
     "active" => "ACTIVE",
     "global_active" => "GLOBAL_ACTIVE",
     "iblock_id" => "IBLOCK_ID",
     "modified_by" => "MODIFIED_BY",
     "created_by" => "CREATED_BY",
     "date_create" => "DATE_CREATE",
     "sort" => "SORT",
     "picture" => "PICTURE",
     "detail_picture" => "DETAIL_PICTURE",
     "socnet_group_id" => "SOCNET_GROUP_ID",
     "description" => "DESCRIPTION",
     "description_type" => "DESCRIPTION_TYPE",
     "searchable_content" => "SEARCHABLE_CONTENT",
     "code" => "CODE",
     "xml_id" => "XML_ID",
     "tmp_id" => "TMP_ID",
     "iblock_section_id" => "IBLOCK_SECTION_ID",
     "left_margin" => "LEFT_MARGIN",
     "right_margin" => "RIGHT_MARGIN",
     "depth_level" => "DEPTH_LEVEL",
     "timestamp_x" => "TIMESTAMP_X",
     "category_id_ozon" => "UF_CATEGORY_ID_OZON",
    ];
  }
}
