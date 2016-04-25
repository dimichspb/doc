<?php

namespace app\components\InnFinder;
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use app\models\Entity;
use app\models\EntityForm;
use app\models\EntityRole;
use app\models\EntityPersonRole;
use app\models\Person;
use app\models\Country;
use app\models\City;
use app\models\Address;
use linslin\yii2\curl\Curl;
use yii\validators\UrlValidator;
use yii\base\InvalidParamException;
 
class InnFinder extends Component
{
    const URL_OGRN_SERVICE_GET_ID = 'http://огрн.онлайн/интеграция/компании/?инн={$request}';
    const URL_OGRN_SERVICE_GET_DETAILS = 'http://огрн.онлайн/интеграция/компании/{$request}/';
    const URL_OGRN_SERVICE_GET_EMPLOYERS = 'http://огрн.онлайн/интеграция/компании/{$request}/сотрудники/';
    
    public function search($inn)
    {
        $entity = Entity::findByInn($inn);
        if (!$entity) {
            switch (strlen($inn)){
                case 10:
                    $entity = $this->searchCompanyViaOGRNService($inn);
                    break;
                case 12:
                    $entity = $this->searchEntrepreneurViaOGRNService($inn);       
            }
        }
        return $entity;
    }
    
    public function searchCompanyViaOGRNService($inn) {
        $getIdUrl = $this->getUrl(InnFinder::URL_OGRN_SERVICE_GET_ID, $inn);
        $idResponse = $this->get($getIdUrl);
        if (is_array($idResponse) && count($idResponse) > 0) {
            $idDetails = $idResponse[0];
            $getDetailsUrl = $this->getUrl(InnFinder::URL_OGRN_SERVICE_GET_DETAILS, $idDetails->id);
            $detailsResponse = $this->get($getDetailsUrl);
            if ($detailsResponse) {
                return $this->parseEntityOGRNService($detailsResponse);
            }
        }
    }
    
    public function getEmployersOGRNService($id) 
    {
        $employers = new \StdClass();
        $getEmployersUrl = $this->getUrl(InnFinder::URL_OGRN_SERVICE_GET_EMPLOYERS, $id);
        $employersResponse = $this->get($getEmployersUrl);
        if (is_array($employersResponse) && count($employersResponse) >0) {
            foreach ($employersResponse as $employeeResponse) {
                if (isset($employeeResponse->post->code)) {
                    $employee = new \StdClass();
                    $employee->firstname = isset($employeeResponse->person->firstName)? $employeeResponse->person->firstName: '';
                    $employee->middlename = isset($employeeResponse->person->middleName)? $employeeResponse->person->middleName: '';
                    $employee->lastname = isset($employeeResponse->person->lastName)? $employeeResponse->person->lastName: '';
                    $employee->rolename = isset($employeeResponse->postName)? $employeeResponse->postName: $employeeResponse->post->name;
                    switch ($employeeResponse->post->code) {
                        case '02':
                            $employers->director = $employee;
                            break;
                        case '03':
                            $employers->accountant = $employee;
                            break;
                        default:
                        
                    }
                }
            }
        }
        return $employers;
    }
    
    public function parseEntityOGRNService(\StdClass $detailsResponse)
    {
        $entity = new Entity();
        $entity->name = $this->parseShortName($detailsResponse);
        $entity->fullname = $this->parseFullName($detailsResponse);
        $entity->ogrn = $this->parseOgrn($detailsResponse);
        $entity->inn = $this->parseInn($detailsResponse);
        $entity->kpp = $this->parseKpp($detailsResponse);
        
        $entityForm = $this->parseEntityForm($detailsResponse);
        if ($entityForm) {
            $entity->entity_form = $entityForm->id;    
        }

        $address = $this->parseAddress($detailsResponse);
        if ($address) {
            $entity->address = $address->id;
            $entity->factaddress = $address->id;    
        }
        
        if ($entity->save()) {
            $employersResponse = $this->getEmployersOGRNService($detailsResponse->id);
            if (isset($employersResponse->director)) {
                $directorPersonRole = $this->parseEmployer($entity, $employersResponse->director);
                if ($directorPersonRole) {
                    $entity->director = $directorPersonRole->id;
                }
            }
            if (isset($employersResponse->accountant)) {
                $accountantPersonRole = $this->parseEmployer($entity, $employersResponse->accountant);
                $entity->accountant = $accountantPersonRole->id;
            }
            $entity->save();
            return $entity;
        }
        //var_dump($entity);
        //die();
    }
    
    public function parseShortName(\StdClass $detailsResponse)
    {
        if (!isset($detailsResponse->shortName)) {
            return '';
        }
        
        $pattern = '/"(.*?)$/';
        $matches = [];
        if (preg_match($pattern, $detailsResponse->shortName, $matches)) {
            return isset($matches[1])? $matches[1]: '';    
        }
    }
    
    public function parseFullName(\StdClass $detailsResponse)
    {
        if (!isset($detailsResponse->name)) {
            return '';
        }
        
        $pattern = '/"(.*?)$/';
        $matches = [];
        if (preg_match($pattern, $detailsResponse->name, $matches)) {
            return isset($matches[1])? $matches[1]: '';    
        }
    }
    
    public function parseOgrn(\StdClass $detailsResponse)
    {
        if (!isset($detailsResponse->ogrn)) {
            return '';
        }
        
        $pattern = '/^[0-9]{13}$/';
        $matches = [];
        if (preg_match($pattern, $detailsResponse->ogrn, $matches)) {
            return isset($matches[0])? $matches[0]: '';    
        }
    }
    
    public function parseInn(\StdClass $detailsResponse)
    {
        if (!isset($detailsResponse->inn)) {
            return '';
        }
        
        $pattern = '/^[0-9]{10}$/';
        $matches = [];
        if (preg_match($pattern, $detailsResponse->inn, $matches)) {
            return isset($matches[0])? $matches[0]: '';    
        }
    }
    
    public function parseKpp(\StdClass $detailsResponse)
    {
        if (!isset($detailsResponse->kpp)) {
            return '';
        }
        
        $pattern = '/^[0-9]{9}$/';
        $matches = [];
        if (preg_match($pattern, $detailsResponse->kpp, $matches)) {
            return isset($matches[0])? $matches[0]: '';    
        }
    }
    
    public function parseEntityForm(\StdClass $detailsResponse)
    {
        $shortName = isset($detailsResponse->shortName)? $detailsResponse->shortName: '';
        $fullName = isset($detailsResponse->name)? $detailsResponse->name: '';
        
        $pattern = '/^(.*?)"/';
        $matches = [];
        if (preg_match($pattern, $shortName, $matches)) {
            $shortEntityFormName = isset($matches[1])? trim($matches[1]): '';
        }
        
        if (preg_match($pattern, $fullName, $matches)) {
            $fullEntityFormName = isset($matches[1])? trim($matches[1]): '';
        }
        
        if (!$entityForm = EntityForm::findByShortName($shortEntityFormName)) {
            $entityForm = new EntityForm();
            $entityForm->name = $shortEntityFormName;
            $entityForm->fullname = $fullEntityFormName;
            $entityForm->save();
        }

        return $entityForm;
    }
    
    public function parseAddress(\StdClass $detailsResponse)
    {
        if (isset($detailsResponse->address)) {
            $country = Country::findFirst();
            $city = isset($detailsResponse->address->city)? $detailsResponse->address->city: $detailsResponse->address->region;
            $cityFullName = $city->typeName . ' ' . $city->name;
            if (!$city = City::findByFullName($cityFullName)) {
                $city = new City();
                $city->name = $cityFullName;
                $city->save();    
            }
            $addressQuery = Address::find();
            $addressQuery->andWhere(['country' => $country->id]);
            $addressQuery->andWhere(['city' => $city->id]);

            if (isset($detailsResponse->address->postalIndex)) {
                $addressQuery->andWhere(['postcode' => $detailsResponse->address->postalIndex]);
            }
            if (isset($detailsResponse->address->street)) {
                $addressQuery->andWhere(['street' => $detailsResponse->address->street->typeShortName . ' ' . $detailsResponse->address->street->name]);
            }
            if (isset($detailsResponse->address->house)) {
                $addressQuery->andWhere(['housenumber' => $detailsResponse->address->house]);
            }
            if (isset($detailsResponse->address->building)) {
                $addressQuery->andWhere(['building' => $detailsResponse->address->building]);
            }
            if (isset($detailsResponse->address->flat)) {
                $addressQuery->andWhere(['office' => $detailsResponse->address->flat]);
            }
            if (!$address = $addressQuery->one()) {
                $address = new Address();
                $address->country = $country->id;
                $address->city = $city->id;
                $address->postcode = isset($detailsResponse->address->postalIndex)? $detailsResponse->address->postalIndex: '';
                $address->street = isset($detailsResponse->address->street)? $detailsResponse->address->street->typeShortName . ' ' . $detailsResponse->address->street->name: '';
                $address->housenumber = isset($detailsResponse->address->house)? $detailsResponse->address->house: '';
                if (isset($detailsResponse->address->building)) {
                    $address->building = $detailsResponse->address->building;
                }
                if (isset($detailsResponse->address->flat)) {
                    $address->office = $detailsResponse->address->flat;
                }
                $address->save();
            }
            return $address;
        }
    }
    
    public function parseEmployer(Entity $entity, \StdClass $employer)
    {
        if (isset($employer->rolename)) {
            $role = $entity->getEntityRoles()->where(['name' => $employer->rolename])->one();
            if (!$role) {
                $role = new EntityRole();
                $role->entity = $entity->id;
                $role->name = $employer->rolename;
                $role->save();
            }
            $personsQuery = $entity->getPersons();
            if (isset($employer->firstname)) {
                $personsQuery->andWhere(['firstname' => $employer->firstname]);
            }
            if (isset($employer->middlename)) {
                $personsQuery->andWhere(['middlename' => $employer->middlename]);
            }
            if (isset($employer->firstname)) {
                $personsQuery->andWhere(['lastname' => $employer->lastname]);
            }
            $person = $personsQuery->one();
            if (!$person) {
                $person = new Person();
                $person->firstname = $employer->firstname;
                $person->middlename = $employer->middlename;
                $person->lastname = $employer->lastname;
                $person->save();
            }
            if ($person && $role) {
                $entityPersonRole = $entity->getEntityPersonRoles()->where([
                    'role' => $role->id,
                    'person' => $person->id,
                ])->one();
                if (!$entityPersonRole) {
                    $entityPersonRole = new EntityPersonRole();
                    $entityPersonRole->entity = $entity->id;
                    $entityPersonRole->person = $person->id;
                    $entityPersonRole->role = $role->id;
                    $entityPersonRole->save();
                }
                return $entityPersonRole;
            }
        }
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