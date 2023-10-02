<?php

namespace Loodsen\Ozon\Services;

use COption;
use Bitrix\Main\Web\HttpClient;
use ErrorException;
use Bitrix\Main\DB\Exception;
use Loodsen\Ozon\References\ConfigList;

class OzonApiClientService
{
  protected $client;
  protected $moduleId;
  protected $ozonApiBaseUrl;
  protected $ozonClientId;
  protected $ozonApiKey;

  public function __construct()
  {
    $this->moduleId = ConfigList::MODULE_ID;

    if (empty(COption::GetOptionString($this->moduleId, 'OZON_CLIENT_ID'))) {
      throw new ErrorException('Необходимо указать Ozon Client-Id в настройках модуля');
    } else {
      $this->ozonClientId = COption::GetOptionString($this->moduleId, 'OZON_CLIENT_ID');
    }

    if (empty(COption::GetOptionString($this->moduleId, 'OZON_API_KEY'))) {
      throw new ErrorException('Необходимо указатьs Ozon Api-Key в настройках модуля');
    } else {
      $this->ozonApiKey = COption::GetOptionString($this->moduleId, 'OZON_API_KEY');
    }

    $this->ozonApiBaseUrl = ConfigList::OZON_API_BASE_URL;

    $this->client = new HttpClient([
       "redirect" => false, // true, если нужно выполнять редиректы
       "redirectMax" => 5, // Максимальное количество редиректов
       "waitResponse" => true,
       "socketTimeout" => 30, // Таймаут соединения, сек
       "streamTimeout" => 60, // Таймаут чтения ответа, сек, 0 - без таймаута
       "version" => HttpClient::HTTP_1_1,
       "compress" => false,
       "disableSslVerification" => false,
       "headers" => [
          'Client-Id' => $this->ozonClientId,
          'Api-Key' => $this->ozonApiKey,
       ],
    ]);
  }

  /**
   * @param  string  $apiMethod
   * @param  array   $post
   *
   * @return array
   */
  public function getData(string $apiMethod, ?array $post = null): array
  {
    try {
      $response = $this->client->post($this->ozonApiBaseUrl . $apiMethod, json_encode($post));
    } catch (Exception $e) {
      return ['error' => $e->getMessage()];
    }

    $data = json_decode($response, true);

    return $data;
  }
}
