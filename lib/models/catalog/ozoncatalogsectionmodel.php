<?php

namespace Loodsen\Ozon\Models\Catalog;

use Bitrix\Main\Type\DateTime;
use Bx\Model\AbsOptimizedModel;

class OzonCatalogSectionModel extends AbsOptimizedModel
{
  /**
   * @return array
   */
  protected function toArray(): array
  {
    return [
     "id" => $this->getId(),
     "name" => $this->getName(),
     "active" => $this->getActive(),
     "global_active" => $this->getGlobalActive(),
     "iblock_id" => $this->getIblockId(),
     "modified_by" => $this->getModifiedBy(),
     "created_by" => $this->getCreatedBy(),
     "date_create" => $this->getDateCreate(),
     "sort" => $this->getSort(),
     "picture" => $this->getPicture(),
     "detail_picture" => $this->getDetailPicture(),
     "socnet_group_id" => $this->getSocnetGroupId(),
     "description" => $this->getDescription(),
     "description_type" => $this->getDescriptionType(),
     "searchable_content" => $this->getSearchableContent(),
     "code" => $this->getCode(),
     "xml_id" => $this->getXmlId(),
     "tmp_id" => $this->getTmpId(),
     "iblock_section_id" => $this->getIblockSectionId(),
     "left_margin" => $this->getLeftMargin(),
     "right_margin" => $this->getRightMargin(),
     "depth_level" => $this->getDepthLevel(),
     "timestamp_x" => $this->getTimestampX(),
     "category_id_ozon" => $this->getCategoryIdOzon(),
    ];
  }


  /**
   * @return int
   */
  public function getId(): int
  {
    return (int)$this["ID"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setId(int $value)
  {
    $this["ID"] = $value;
  }


  /**
   * @return string
   */
  public function getName(): string
  {
    return (string)$this["NAME"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setName(string $value)
  {
    $this["NAME"] = $value;
  }


  /**
   * @return string
   */
  public function getActive(): string
  {
    return (string)$this["ACTIVE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setActive(string $value)
  {
    $this["ACTIVE"] = $value;
  }


  /**
   * @return string
   */
  public function getGlobalActive(): string
  {
    return (string)$this["GLOBAL_ACTIVE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setGlobalActive(string $value)
  {
    $this["GLOBAL_ACTIVE"] = $value;
  }


  /**
   * @return int
   */
  public function getIblockId(): int
  {
    return (int)$this["IBLOCK_ID"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setIblockId(int $value)
  {
    $this["IBLOCK_ID"] = $value;
  }


  /**
   * @return int
   */
  public function getModifiedBy(): int
  {
    return (int)$this["MODIFIED_BY"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setModifiedBy(int $value)
  {
    $this["MODIFIED_BY"] = $value;
  }


  /**
   * @return int
   */
  public function getCreatedBy(): int
  {
    return (int)$this["CREATED_BY"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setCreatedBy(int $value)
  {
    $this["CREATED_BY"] = $value;
  }


  /**
   * @return ?DateTime
   */
  public function getDateCreate(): ?DateTime
  {
    return $this["DATE_CREATE"] instanceof DateTime ? $this["DATE_CREATE"] : null;
  }


  /**
   * @param DateTime $value
   * @return void
   */
  public function setDateCreate(DateTime $value)
  {
    $this["DATE_CREATE"] = $value;
  }


  /**
   * @return int
   */
  public function getSort(): int
  {
    return (int)$this["SORT"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setSort(int $value)
  {
    $this["SORT"] = $value;
  }


  /**
   * @return int
   */
  public function getPicture(): int
  {
    return (int)$this["PICTURE"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setPicture(int $value)
  {
    $this["PICTURE"] = $value;
  }


  /**
   * @return int
   */
  public function getDetailPicture(): int
  {
    return (int)$this["DETAIL_PICTURE"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setDetailPicture(int $value)
  {
    $this["DETAIL_PICTURE"] = $value;
  }


  /**
   * @return int
   */
  public function getSocnetGroupId(): int
  {
    return (int)$this["SOCNET_GROUP_ID"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setSocnetGroupId(int $value)
  {
    $this["SOCNET_GROUP_ID"] = $value;
  }


  /**
   * @return string
   */
  public function getDescription(): string
  {
    return (string)$this["DESCRIPTION"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setDescription(string $value)
  {
    $this["DESCRIPTION"] = $value;
  }


  /**
   * @return string
   */
  public function getDescriptionType(): string
  {
    return (string)$this["DESCRIPTION_TYPE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setDescriptionType(string $value)
  {
    $this["DESCRIPTION_TYPE"] = $value;
  }


  /**
   * @return string
   */
  public function getSearchableContent(): string
  {
    return (string)$this["SEARCHABLE_CONTENT"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setSearchableContent(string $value)
  {
    $this["SEARCHABLE_CONTENT"] = $value;
  }


  /**
   * @return string
   */
  public function getCode(): string
  {
    return (string)$this["CODE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setCode(string $value)
  {
    $this["CODE"] = $value;
  }


  /**
   * @return string
   */
  public function getXmlId(): string
  {
    return (string)$this["XML_ID"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setXmlId(string $value)
  {
    $this["XML_ID"] = $value;
  }


  /**
   * @return string
   */
  public function getTmpId(): string
  {
    return (string)$this["TMP_ID"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setTmpId(string $value)
  {
    $this["TMP_ID"] = $value;
  }


  /**
   * @return int
   */
  public function getIblockSectionId(): int
  {
    return (int)$this["IBLOCK_SECTION_ID"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setIblockSectionId(int $value)
  {
    $this["IBLOCK_SECTION_ID"] = $value;
  }


  /**
   * @return int
   */
  public function getLeftMargin(): int
  {
    return (int)$this["LEFT_MARGIN"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setLeftMargin(int $value)
  {
    $this["LEFT_MARGIN"] = $value;
  }


  /**
   * @return int
   */
  public function getRightMargin(): int
  {
    return (int)$this["RIGHT_MARGIN"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setRightMargin(int $value)
  {
    $this["RIGHT_MARGIN"] = $value;
  }


  /**
   * @return int
   */
  public function getDepthLevel(): int
  {
    return (int)$this["DEPTH_LEVEL"];
  }


  /**
   * @param int $value
   * @return void
   */
  public function setDepthLevel(int $value)
  {
    $this["DEPTH_LEVEL"] = $value;
  }


  /**
   * @return ?DateTime
   */
  public function getTimestampX(): ?DateTime
  {
    return $this["TIMESTAMP_X"] instanceof DateTime ? $this["TIMESTAMP_X"] : null;
  }


  /**
   * @param DateTime $value
   * @return void
   */
  public function setTimestampX(DateTime $value)
  {
    $this["TIMESTAMP_X"] = $value;
  }

  /**
   * @return string
   */
  public function getCategoryIdOzon(): string
  {
    return (string)$this["UF_CATEGORY_ID_OZON"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setCategoryIdOzon(string $value)
  {
    $this["UF_CATEGORY_ID_OZON"] = $value;
  }
}
