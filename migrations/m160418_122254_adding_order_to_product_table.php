<?php

use yii\db\Migration;

class m160418_122254_adding_order_to_product_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%order_to_product}}', [
            'order' => $this->integer(11),
            'product' => $this->integer(11),
            'quantity' => $this->integer(11)->notNull()->defaultValue(0),
            'price' => $this->float()->notNull()->defaultValue(0),
        ]);
        $this->addPrimaryKey('pk_order_to_product', '{{%order_to_product}}', ['order', 'product']);
        $this->addForeignKey('fk_order_to_product_order_id', '{{%order_to_product}}', 'order', '{{%order}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_order_to_product_product_id', '{{%order_to_product}}', 'product', '{{%product}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%order_to_product}}');
    }
}
