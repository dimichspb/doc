<?php

use yii\db\Migration;

class m160419_090059_adding_delivery_to_product_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%delivery_to_product}}', [
            'delivery' => $this->integer(11),
            'product' => $this->integer(11),
            'quantity' => $this->integer(11)->notNull()->defaultValue(0),
        ]);
        $this->addPrimaryKey('pk_delivery_to_product', '{{%delivery_to_product}}', ['delivery', 'product']);
        $this->addForeignKey('fk_delivery_to_product_delivery_id', '{{%delivery_to_product}}', 'delivery', '{{%delivery}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_delivery_to_product_product_id', '{{%delivery_to_product}}', 'product', '{{%product}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%delivery_to_product}}');
    }
}
