<?php

use yii\db\Migration;

class m160419_070649_adding_payment_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(11),
            'status' => $this->integer(2)->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'order' => $this->integer(11)->notNull(),
            'entity' => $this->integer(11)->notNull(),
            'amount' => $this->float()->notNull()->defaultValue(0),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('fk_payment_order_id', '{{%payment}}', 'order', '{{%order}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_payment_entity_id', '{{%payment}}', 'entity', '{{%entity}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%payment}}');
    }

}
