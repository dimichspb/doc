<?php

use yii\db\Migration;

class m160329_113612_adding_address_to_entity_relation_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%address_to_entity}}', [
            'address' => $this->integer(11)->notNull(),
            'entity' => $this->integer(11)->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('fk_address_to_entity_address_id', '{{%address_to_entity}}', 'address', '{{%address}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_address_to_entity_entity_id', '{{%address_to_entity}}', 'entity', '{{%entity}}', 'id', 'RESTRICT', 'CASCADE');

        $entityId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%entity}}')
            ->min('id');

        $addressId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%address}}')
            ->min('id');

        $this->insert('{{%address_to_entity}}', [
            'address' => $addressId,
            'entity' => $entityId,
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%address_to_entity}}');
    }

}
