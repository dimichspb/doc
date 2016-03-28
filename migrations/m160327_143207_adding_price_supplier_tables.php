<?php

use yii\db\Migration;

class m160327_143207_adding_price_supplier_tables extends Migration
{
    public function up()
    {
        $this->createTable('{{%price}}', [
            'id' => $this->primaryKey(11),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'started_at' => $this->integer()->notNull(),
            'expire_at' => $this->integer(),
            'product' => $this->integer(11)->notNull(),
            'supplier' => $this->integer(11)->notNull(),
            'quantity' => $this->integer(11)->notNull()->defaultValue(0),
            'value' => $this->float()->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->createTable('{{%supplier}}', [
            'id' => $this->primaryKey(11),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'name' => $this->string(255),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('fk_price_product_id', '{{%price}}', 'product', '{{%product}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_price_supplier_id', '{{%price}}', 'supplier', '{{%supplier}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('fk_price_supplier_id', 'price');
        $this->dropForeignKey('fk_price_product_id', 'price');
        $this->dropTable('{{%supplier}}');
        $this->dropTable('{{%price}}');
    }
}
