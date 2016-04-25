<?php

namespace app\components;
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use app\models\Entity;
use linslin\yii2\curl\Curl;
use yii\validators\UrlValidator;
use yii\base\InvalidParamException;
 
class InnFinder extends Component
{
    const URL_OGRN_SERVICE_GET_ID = 'http://огрн.онлайн/интеграция/компании/?инн={$request}';
    const URL_OGRN_SERVICE_GET_DETAILS = 'http://огрн.онлайн/интеграция/компании/{$request}/';
    
    public function search($inn)
    {
        return $this->searchViaOGRNService($inn);
    }
    
    public function searchViaOGRNService($inn) {
        $entity = new Entity();
        $getIdUrl = $this->getUrl(InnFinder::URL_OGRN_SERVICE_GET_ID, $inn);
        $idResponse = $this->get($getIdUrl);
        if (is_array($idResponse)) {
            $idDetails = $idResponse[0];
            $getDetailsUrl = $this->getUrl(InnFinder::URL_OGRN_SERVICE_GET_DETAILS, $idDetails->id);
            $detailsResponse = $this->get($getDetailsUrl);
            if (isset($detailsResponse->name)) {
                $entity->name = $detailsResponse->shortName;
            }
        }
        return $entity; 
    }
    
    public function get($url) {
        $curl = new Curl();
        return json_decode($curl->get($url));
    }
    
    public function getUrl($urlPattern, $request)
    {
        return str_replace('{$request}', $request, $urlPattern);
    }
}