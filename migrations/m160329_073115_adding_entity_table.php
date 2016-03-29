<?php

use yii\db\Migration;

class m160329_073115_adding_entity_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%entity_form}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(4)->notNull()->unique(),
            'fullname' => $this->string(255)->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->batchInsert('{{%entity_form}}',
            ['name', 'fullname'], [
            ['ООО',  'Общество с ограниченной ответственностью'],
            ['ОАО',  'Открытое акционерное общество'],
            ['ЗАО',  'Закрытое акционерное общество'],
            ['ПАО',  'Публичное акфионерное общество'],
            ['ИП',   'Индивидуальный предприниматель'],
            ['ГУ',   'Государственное учреждение'],
            ]);

        $guId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%entity_form}}')
            ->where(['name' => 'ГУ']);

        $paoId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%entity_form}}')
            ->where(['name' => 'ПАО']);

        $oooId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%entity_form}}')
            ->where(['name' => 'ООО']);

        $this->createTable('{{%country}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(255)->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->insert('{{%country}}', [
            'name' => 'Россия',
        ]);

        $countryId = $this->db->lastInsertID;

        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(255)->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->insert('{{$city}}', [
            'name' => 'Санкт-Петербург',
        ]);

        $cityId = $this->db->lastInsertID;

        $this->createTable('{{%address}}', [
            'id' => $this->primaryKey(11),
            'country' => $this->integer(11)->notNull()->defaultValue($countryId),
            'city' => $this->integer(11)->notNull()->defaultValue($cityId),
            'postcode' => $this->string(6)->notNull(),
            'street' => $this->string(255)->notNull(),
            'housenumber' => $this->string(10)->notNull(),
            'building' => $this->string(10),
            'office' => $this->string(10),
            'comments' => $this->string(255),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('fk_address_country_id', '{{%address}}', 'country', '{{%country}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_address_city_id', '{{%address}}', 'city', '{{%city}}', 'id', 'RESTRICT', 'CASCADE');

        $this->insert('{{%address}}', [
            'country' => $countryId,
            'city' => $cityId,
            'postcode' => '197371',
            'street' => 'Королева пр-кт',
            'housenumber' => '45',
            'building' => '1',
            'office' => '26',
        ]);

        $addressId = $this->db->lastInsertID;

        $this->createTable('{{%account}}', [
            'id' => $this->primaryKey(11),
            'bank' => $this->integer(11)->notNull(),
            'number' => $this->string(20)->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->createTable('{{%bank}}', [
            'id' => $this->primaryKey(11),
            'entity_form' => $this->integer(11)->notNull(),
            'name' => $this->string(255)->notNull(),
            'code' => $this->string(9)->notNull(),
            'account' => $this->integer(11)->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->insert('{{%bank}}', [
            'entity_form' => $guId,
            'name' => 'Северо-Западное ГУ Банка России',
            'code' => '044030001',
            'account' => '1',
        ]);

        $bankId = $this->db->lastInsertID;

        $this->insert('{{%account}}', [
            'bank' => $bankId,
            'number' => '40101810200000010001',
        ]);

        $accountId = $this->db->lastInsertID;

        $this->addForeignKey('fk_bank_account_id', '{{%bank}}', 'account', '{{%account}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_account_bank_id', '{{%account}}', 'bank', '{{%bank}}', 'id', 'RESTRICT', 'CASCADE');

        $this->insert('{{%account}}', [
            'bank' => $bankId,
            'number' => '30101810900000000790',
        ]);

        $accountId = $this->db->lastInsertID;

        $this->insert('{{%bank}}', [
            'entity_form' => $paoId,
            'name' => '«Банк «Санкт-Петербург»',
            'code' => '044030790',
            'account' => $accountId,
        ]);

        $bankId = $this->db->lastInsertID;

        $this->createTable('{{%person}}', [
            'id' => $this->primaryKey(11),
            'firstname' => $this->string(255)->notNull(),
            'lastname' => $this->string(255)->notNull(),
            'middlename' => $this->string(255),
            'birthday' => $this->integer(11),
        ], "DEFAULT CHARSET=utf8");

        $this->insert('{{%person}}', [
            'firstname' => 'Иван',
            'lastname' => 'Иванов',
            'middlename' => 'Иванович',
            'birthday' => '01.01.2000',
        ]);

        $personId = $this->db->lastInsertID;

        $this->createTable('{{%entity_role', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(255),
        ], "DEFAULT CHARSET=utf8");

        $this->insert('{{%entity_role}}', [
            'name' => 'Генеральный директор',
        ]);

        $directorId = $this->db->lastInsertID;

        $this->insert('{{%entity_role}}', [
           'name' => 'Главный бухгалтер',
        ]);

        $accountantId = $this->db->lastInsertID;

        $this->createTable('{{%entity_person_role}}', [
            'id' => $this->primaryKey(11),
            'entity' => $this->integer(11)->notNull(),
            'role' => $this->integer(11)->notNull(),
            'person' => $this->integer(11)->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->createTable('{{%entity}}', [
            'id' => $this->primaryKey(11),
            'status' => $this->integer(2)->notNull()->defaultValue(10),
            'created_by' => $this->integer(11)->notNull(),
            'entity_form' => $this->integer(11)->notNull(),
            'name' => $this->string(255),
            'fullname' => $this->string(255),
            'ogrn' => $this->string(13)->unique(),
            'inn'  => $this->string(12)->notNull()->unique(),
            'kpp'  => $this->string(9),
            'address' => $this->integer(11),
            'factaddress' => $this->integer(11),
            'account' => $this->integer(11),
            'director' => $this->integer(11),
            'accountant' => $this->integer(11),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('fk_entity_created_by_user_id', '{{%entity}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_entity_entity_form_id', '{{%entity}}', 'entity_form', '{{%entity_form}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_entity_address_id', '{{%entity}}', 'address', '{{%address}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_entity_factaddress_id', '{{%entity}}', 'factaddress', '{{%address}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_entity_account_id', '{{%entity}}', 'account', '{{%account}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_entity_director_entity_role_id', '{{%entity}}', 'director', '{{%entity_person_role}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_entity_accountant_entity_role_id', '{{%entity}}', 'accountant', '{{%entity_person_role}}', 'id', 'RESTRICT', 'CASCADE');


        $adminId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%user}}')
            ->min('id');

        $this->insert('{{%entity}}', [
            'created_by' => $adminId,
            'entity_form' => $oooId,
            'name' => 'Первая',
            'fullname' => 'Первая компания',
            'ogrn' => '0000000000000',
            'inn' =>  '000000000000',
            'kpp' =>  '000000000',
            'address' => $addressId,
            'factaddress' => $addressId,
            'account' => $accountId,
        ]);

        $entityId = $this->db->lastInsertID;

        $this->insert('{{%entity_person_role}}', [
            'entity' => $entityId,
            'role' => $directorId,
            'person' => $personId,
        ]);

        $directorRoleId = $this->db->lastInsertID;

        $this->insert('{{%entity_person_role}}', [
            'entity' => $entityId,
            'role' => $accountantId,
            'person' => $personId,
        ]);

        $accountantRoleId = $this->db->lastInsertID;

        $this->update('{{%entity}}', [
            'director' => $directorRoleId,
            'accountant' => $accountantRoleId,
        ], ['id' => $entityId]);

    }

    public function down()
    {
        $this->dropTable('{{%entity}}');
        $this->dropTable('{{%entity_person_role}}');
        $this->dropTable('{{%entity_role}}');
        $this->dropTable('{{%person}}');
        $this->dropForeignKey('fk_account_bank_id', '{{%account}}');
        $this->dropForeignKey('fk_bank_account_id', '{{%bank}}');
        $this->dropTable('{{%bank}}');
        $this->dropTable('{{%account}}');
        $this->dropTable('{{%address}}');
        $this->dropTable('{{%city}}');
        $this->dropTable('{{%country}}');
        $this->dropTable('{{%entity_form}}');
    }
}
