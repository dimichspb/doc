<?php

use yii\db\Migration;

class m160329_095848_adding_customer_to_entity_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%customer_to_entity}}',[
            'customer' => $this->integer(11)->notNull(),
            'entity' => $this->integer(11)->notNull(),
        ], "DEFAULT CHARSET=utf8");
        $this->addForeignKey('fk_customer_to_entity_customer_id', '{{%customer_to_entity}}', 'customer', '{{%customer}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_customer_to_entity_entity_id', '{{%customer_to_entity}}', 'entity', '{{%entity}}', 'id', 'RESTRICT', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('{{%customer_to_entity}}');
    }
}
