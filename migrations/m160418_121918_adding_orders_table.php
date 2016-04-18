<?php

use yii\db\Migration;

class m160418_121918_adding_orders_table extends Migration
{
 public function up()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(11),
            'status' => $this->integer(2)->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'expire_at' => $this->integer(),
            'quotation' => $this->integer(11)->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('fk_order_quotation_id', '{{%order}}', 'quotation', '{{%quotation}}' , 'id', 'CASCADE', 'CASCADE');
        
        $today = new \DateTime();
        $quotationId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%quotation}}')
            ->min('id');
        $this->insert('{{%order}}', [
            'created_at' => $today->getTimestamp(),
            'updated_at' => $today->getTimestamp(),
            'expire_at' => $today->add(new \DateInterval('P1W'))->getTimestamp(),
            'quotation' => $quotationId,
        ]);
    }

    public function down()
    {
        $this->dropForeignKey('fk_order_quotation_id', '{{%order}}');
        $this->dropTable('{{%order}}');
    }
}
