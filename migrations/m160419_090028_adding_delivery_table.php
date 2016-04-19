<?php

use yii\db\Migration;

class m160419_090028_adding_delivery_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%delivery}}', [
            'id' => $this->primaryKey(11),
            'status' => $this->integer(2)->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'delivered_at' => $this->integer(),
            'order' => $this->integer(11)->notNull(),
            'shipper' => $this->integer(11)->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('fk_delivery_order_id', '{{%delivery}}', 'order', '{{%order}}' , 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_delivery_shipper_id', '{{%delivery}}', 'shipper', '{{%shipper}}' , 'id', 'RESTRICT', 'CASCADE');
      
    }

    public function down()
    {
        $this->dropTable('{{%delivery}}');
    }
}
