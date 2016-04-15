<?php

use yii\db\Migration;

class m160415_072048_adding_primary_key_to_supplier_and_customer_to_entity_tables extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_supplier_to_entity_supplier_id', '{{%supplier_to_entity}}');
        $this->dropForeignKey('fk_supplier_to_entity_entity_id', '{{%supplier_to_entity}}');

        $this->addPrimaryKey('pk_supplier_to_entity', '{{%supplier_to_entity}}', ['supplier', 'entity']);

        $this->addForeignKey('fk_supplier_to_entity_supplier_id', '{{%supplier_to_entity}}', 'supplier', '{{%supplier}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_supplier_to_entity_entity_id', '{{%supplier_to_entity}}', 'entity', '{{%entity}}', 'id', 'RESTRICT', 'CASCADE');



        $this->dropForeignKey('fk_customer_to_entity_customer_id', '{{%customer_to_entity}}');
        $this->dropForeignKey('fk_customer_to_entity_entity_id', '{{%customer_to_entity}}');

        $this->addPrimaryKey('pk_customer_to_entity', '{{%customer_to_entity}}', ['customer', 'entity']);

        $this->addForeignKey('fk_customer_to_entity_customer_id', '{{%customer_to_entity}}', 'customer', '{{%customer}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_customer_to_entity_entity_id', '{{%customer_to_entity}}', 'entity', '{{%entity}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_supplier_to_entity_supplier_id', '{{%supplier_to_entity}}');
        $this->dropForeignKey('fk_supplier_to_entity_entity_id', '{{%supplier_to_entity}}');

        $this->dropPrimaryKey('pk_supplier_to_entity', '{{%supplier_to_entity}}');

        $this->addForeignKey('fk_supplier_to_entity_supplier_id', '{{%supplier_to_entity}}', 'supplier', '{{%supplier}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_supplier_to_entity_entity_id', '{{%supplier_to_entity}}', 'entity', '{{%entity}}', 'id', 'RESTRICT', 'CASCADE');



        $this->dropForeignKey('fk_customer_to_entity_customer_id', '{{%customer_to_entity}}');
        $this->dropForeignKey('fk_customer_to_entity_entity_id', '{{%customer_to_entity}}');

        $this->dropPrimaryKey('pk_customer_to_entity', '{{%customer_to_entity}}');

        $this->addForeignKey('fk_customer_to_entity_customer_id', '{{%customer_to_entity}}', 'customer', '{{%customer}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_customer_to_entity_entity_id', '{{%customer_to_entity}}', 'entity', '{{%entity}}', 'id', 'RESTRICT', 'CASCADE');

    }
}
