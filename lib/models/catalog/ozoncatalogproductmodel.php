<?php

namespace Loodsen\Ozon\Models\Catalog;

use Bitrix\Main\Type\DateTime;
use Bx\Model\AbsOptimizedModel;

class OzonCatalogProductModel extends AbsOptimizedModel
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
			"iblock_id" => $this->getIblockId(),
			"date_create" => $this->getDateCreate(),
			"active_from" => $this->getActiveFrom(),
			"active_to" => $this->getActiveTo(),
			"sort" => $this->getSort(),
			"preview_picture" => $this->getPreviewPicture(),
			"preview_text" => $this->getPreviewText(),
			"detail_picture" => $this->getDetailPicture(),
			"detail_text" => $this->getDetailText(),
			"code" => $this->getCode(),
			"tags" => $this->getTags(),
			"iblock_section_id" => $this->getIblockSectionId(),
			"iblock_section" => $this->getIblockSection(),
			"timestamp_x" => $this->getTimestampX(),
			"brand" => $this->getBrand(),
			"partnumber" => $this->getPartnumber(),
			"marka_ts" => $this->getMarkaTs(),
			"modelts" => $this->getModelts(),
			"type" => $this->getType(),
			"artikul" => $this->getArtikul(),
			"strana_izgotovitel" => $this->getStranaIzgotovitel(),
			"garanty_term" => $this->getGarantyTerm(),
			"technique_type" => $this->getTechniqueType(),
			"amount_package" => $this->getAmountPackage(),
			"equipment" => $this->getEquipment(),
			"alternate_artikul" => $this->getAlternateArtikul(),
			"amount" => $this->getAmount(),
			"carving" => $this->getCarving(),
			"product_id" => $this->getProductId(),
			"offer_id" => $this->getOfferId(),
			"images_list" => $this->getImagesList(),
			"discount" => $this->getDiscount(),
			"height" => $this->getHeight(),
			"width" => $this->getWidth(),
			"depth" => $this->getDepth(),
			"dimension_unit" => $this->getDimensionUnit(),
			"weight" => $this->getWeight(),
			"weight_unit" => $this->getWeightUnit(),
			"currency_code" => $this->getCurrencyCode(),
			"price" => $this->getPrice(),
			"marketing_price" => $this->getMarketingPrice(),
			"old_price" => $this->getOldPrice(),
			"min_price" => $this->getMinPrice(),
			"fbs" => $this->getFbs(),
			"fbo" => $this->getFbo(),
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
	 * @return ?DateTime
	 */
	public function getActiveFrom(): ?DateTime
	{
		return $this["ACTIVE_FROM"] instanceof DateTime ? $this["ACTIVE_FROM"] : null;
	}


	/**
	 * @param DateTime $value
	 * @return void
	 */
	public function setActiveFrom(DateTime $value)
	{
		$this["ACTIVE_FROM"] = $value;
	}


	/**
	 * @return ?DateTime
	 */
	public function getActiveTo(): ?DateTime
	{
		return $this["ACTIVE_TO"] instanceof DateTime ? $this["ACTIVE_TO"] : null;
	}


	/**
	 * @param DateTime $value
	 * @return void
	 */
	public function setActiveTo(DateTime $value)
	{
		$this["ACTIVE_TO"] = $value;
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
	public function getPreviewPicture(): int
	{
		return (int)$this["PREVIEW_PICTURE"];
	}


	/**
	 * @param int $value
	 * @return void
	 */
	public function setPreviewPicture(int $value)
	{
		$this["PREVIEW_PICTURE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getPreviewText(): string
	{
		return (string)$this["PREVIEW_TEXT"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setPreviewText(string $value)
	{
		$this["PREVIEW_TEXT"] = $value;
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
	 * @return string
	 */
	public function getDetailText(): string
	{
		return (string)$this["DETAIL_TEXT"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setDetailText(string $value)
	{
		$this["DETAIL_TEXT"] = $value;
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
	public function getTags(): string
	{
		return (string)$this["TAGS"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setTags(string $value)
	{
		$this["TAGS"] = $value;
	}


	/**
	 * @return string
	 */
	public function getIblockSectionId(): string
	{
		return (string)$this["IBLOCK_SECTION_ID"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setIblockSectionId(string $value)
	{
		$this["IBLOCK_SECTION_ID"] = $value;
	}


  /**
   * @return array
   */
  public function getIblockSection(): array
  {
    return (array)$this["IBLOCK_SECTION"];
  }


  /**
   * @param array $value
   * @return void
   */
  public function setIblockSection(array $value)
  {
    $this["IBLOCK_SECTION"] = $value;
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
	public function getBrand(): string
	{
		return (string)$this["BRAND_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setBrand(string $value)
	{
		$this["BRAND_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getPartnumber(): string
	{
		return (string)$this["PARTNUMBER_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setPartnumber(string $value)
	{
		$this["PARTNUMBER_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getMarkaTs(): string
	{
		return (string)$this["MARKA_TS_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setMarkaTs(string $value)
	{
		$this["MARKA_TS_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getModelts(): string
	{
		return (string)$this["MODELTS_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setModelts(string $value)
	{
		$this["MODELTS_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getType(): string
	{
		return (string)$this["TYPE_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setType(string $value)
	{
		$this["TYPE_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getArtikul(): string
	{
		return (string)$this["ARTIKUL_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setArtikul(string $value)
	{
		$this["ARTIKUL_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getStranaIzgotovitel(): string
	{
		return (string)$this["STRANA_IZGOTOVITEL_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setStranaIzgotovitel(string $value)
	{
		$this["STRANA_IZGOTOVITEL_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getGarantyTerm(): string
	{
		return (string)$this["GARANTY_TERM_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setGarantyTerm(string $value)
	{
		$this["GARANTY_TERM_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getTechniqueType(): string
	{
		return (string)$this["TECHNIQUE_TYPE_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setTechniqueType(string $value)
	{
		$this["TECHNIQUE_TYPE_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getAmountPackage(): string
	{
		return (string)$this["AMOUNT_PACKAGE_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setAmountPackage(string $value)
	{
		$this["AMOUNT_PACKAGE_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getEquipment(): string
	{
		return (string)$this["EQUIPMENT_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setEquipment(string $value)
	{
		$this["EQUIPMENT_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getAlternateArtikul(): string
	{
		return (string)$this["ALTERNATE_ARTIKUL_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setAlternateArtikul(string $value)
	{
		$this["ALTERNATE_ARTIKUL_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getAmount(): string
	{
		return (string)$this["AMOUNT_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setAmount(string $value)
	{
		$this["AMOUNT_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getCarving(): string
	{
		return (string)$this["CARVING_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setCarving(string $value)
	{
		$this["CARVING_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getProductId(): string
	{
		return (string)$this["PRODUCT_ID_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setProductId(string $value)
	{
		$this["PRODUCT_ID_VALUE"] = $value;
	}


	/**
	 * @return string
	 */
	public function getOfferId(): string
	{
		return (string)$this["OFFER_ID_VALUE"];
	}


	/**
	 * @param string $value
	 * @return void
	 */
	public function setOfferId(string $value)
	{
		$this["OFFER_ID_VALUE"] = $value;
	}

  /**
   * @return string
   */
  public function getImagesList(): string
  {
    return (string)$this["IMAGES_LIST_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setImages(string $value)
  {
    $this["IMAGES_LIST_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getDiscount(): string
  {
    return (string)$this["DISCOUNT_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setDiscount(string $value)
  {
    $this["DISCOUNT_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getHeight(): string
  {
    return (string)$this["HEIGHT_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setHeight(string $value)
  {
    $this["HEIGHT_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getWidth(): string
  {
    return (string)$this["WIDTH_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setWidth(string $value)
  {
    $this["WIDTH_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getDepth(): string
  {
    return (string)$this["DEPTH_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setDepth(string $value)
  {
    $this["DEPTH_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getDimensionUnit(): string
  {
    return (string)$this["DIMENSION_UNIT_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setDimensionUnit(string $value)
  {
    $this["DIMENSION_UNIT_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getWeight(): string
  {
    return (string)$this["WEIGHT_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setWeight(string $value)
  {
    $this["WEIGHT_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getWeightUnit(): string
  {
    return (string)$this["WEIGHT_UNIT_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setWeightUnit(string $value)
  {
    $this["WEIGHT_UNIT_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getCurrencyCode(): string
  {
    return (string)$this["CURRENCY_CODE_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setCurrencyCode(string $value)
  {
    $this["CURRENCY_CODE_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getPrice(): string
  {
    return (string)$this["PRICE_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setPrice(string $value)
  {
    $this["PRICE_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getMarketingPrice(): string
  {
    return (string)$this["MARKETING_PRICE_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setMarketingPrice(string $value)
  {
    $this["MARKETING_PRICE_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getOldPrice(): string
  {
    return (string)$this["OLD_PRICE_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setOldPrice(string $value)
  {
    $this["OLD_PRICE_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getMinPrice(): string
  {
    return (string)$this["MIN_PRICE_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setMinPrice(string $value)
  {
    $this["MIN_PRICE_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getFbs(): string
  {
    return (string)$this["FBS_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setFbs(string $value)
  {
    $this["FBS_VALUE"] = $value;
  }


  /**
   * @return string
   */
  public function getFbo(): string
  {
    return (string)$this["FBO_VALUE"];
  }


  /**
   * @param string $value
   * @return void
   */
  public function setFbo(string $value)
  {
    $this["FBO_VALUE"] = $value;
  }
}
