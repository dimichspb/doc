<?php

use yii\db\Migration;
use app\models\Entity;
use app\models\EntityForm;
use app\models\Address;
use app\models\Country;
use app\models\City;
use app\models\Account;
use app\models\Bank;
use app\models\Person;
use app\models\EntityRole;
use app\models\EntityPersonRole;


class m160604_130024_adding_stroyuspeh_entity_details extends Migration
{
    public function up()
    {
        if ($entity = Entity::findByInn('7813223912')) {
            return true;
        }
        $entity = new Entity();
        $entity->ogrn = '1157847205669';
        $entity->inn = '7813223912';
        $entity->kpp = '781301001';
        $entity->entity_form = EntityForm::findByShortName('ООО')->id;
        $entity->name = 'СтройУспех';
        $entity->fullname = 'СтройУспех';

        $entity->save();

        if (!$bank = Bank::findByCode('044030811')) {
            $bank = new Bank();
            $bank->code = '044030811';
            $bank->name = 'Филиал № 7806 ВТБ 24';
            $bank->entity_form = EntityForm::findByShortName('ПАО')->id;
            $bankAccount = new Account();
            $bankAccount->number = '30101810300000000811';
            $bankAccount->bank = Bank::findByCode('044030001')->id;
            $bankAccount->save();
            $bank->account = $bankAccount->id;
            $bank->save();
        }

        $account = new Account();
        $account->entity = $entity->id;
        $account->number = '40702810332260009782';
        $account->bank = $bank->id;
        $account->save();

        $entity->account = $account->id;

        $entity->save();

        $directorRole = new EntityRole();
        $directorRole->entity = $entity->id;
        $directorRole->name = 'Генеральный директор';
        $directorRole->save();

        $accountantRole = new EntityRole();
        $accountantRole->entity = $entity->id;
        $accountantRole->name = 'Главный бухгалтер';
        $accountantRole->save();

        $firstPerson = new Person();
        $firstPerson->firstname = 'Екатерина';
        $firstPerson->middlename = 'Сергеевна';
        $firstPerson->lastname = 'Булгакова';
        $firstPerson->save();

        $secondPerson = new Person();
        $secondPerson->firstname = 'Екатерина';
        $secondPerson->middlename = 'Сергеевна';
        $secondPerson->lastname = 'Ерошина';
        $secondPerson->save();

        $directorPersonRole = new EntityPersonRole();
        $directorPersonRole->entity = $entity->id;
        $directorPersonRole->person = $firstPerson->id;
        $directorPersonRole->role = $directorRole->id;
        $directorPersonRole->save();

        $accountantPersonRole = new EntityPersonRole();
        $accountantPersonRole->entity = $entity->id;
        $accountantPersonRole->person = $secondPerson->id;
        $accountantPersonRole->role = $accountantRole->id;
        $accountantPersonRole->save();

        $entity->director = $directorPersonRole->id;
        $entity->accountant = $accountantPersonRole->id;

        $address = new Address();
        $address->country = Country::findByFullName('Россия')->id;
        $address->city = City::findByFullName('Санкт-Петербург')->id;
        $address->street = 'Большая Монетная ул.';
        $address->housenumber = '30';
        $address->building = 'Лит. А';
        $address->office = 'Пом. 12-Н';
        $address->postcode = '197101';
        $address->save();

        $entity->address = $address->id;
        $entity->factaddress = $address->id;
        $entity->save();

        $address->link('entities', $entity);
    }

    public function down()
    {

    }
}
