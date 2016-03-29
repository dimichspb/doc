<?php

use yii\db\Migration;

class m160329_095859_adding_supplier_to_entity_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%supplier_to_entity}}',[
            'supplier' => $this->integer(11)->notNull(),
            'entity' => $this->integer(11)->notNull(),
        ], "DEFAULT CHARSET=utf8");
        $this->addForeignKey('fk_supplier_to_entity_supplier_id', '{{%supplier_to_entity}}', 'supplier', '{{%supplier}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_supplier_to_entity_entity_id', '{{%supplier_to_entity}}', 'entity', '{{%entity}}', 'id', 'RESTRICT', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('{{%supplier_to_entity}}');
    }
}
