<?php

use yii\db\Migration;

class m160328_133831_adding_quotation_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%quotation}}', [
            'id' => $this->primaryKey(11),
            'status' => $this->integer(2)->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'expire_at' => $this->integer(),
            'request' => $this->integer(11)->notNull(),
            'supplier' => $this->integer(11)->notNull(),
            'value' => $this->float()->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('fk_quotation_request_id', '{{%quotation}}', 'request', '{{%request}}' , 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_quotation_supplier_id', '{{%quotation}}', 'supplier', '{{%supplier}}', 'id', 'CASCADE', 'CASCADE');

        $today = new \DateTime();
        $requestId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%request}}')
            ->min('id');
        $supplierId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%supplier}}')
            ->min('id');
        $this->insert('{{%quotation}}', [
            'created_at' => $today->getTimestamp(),
            'updated_at' => $today->getTimestamp(),
            'expire_at' => $today->add(new \DateInterval('P1W'))->getTimestamp(),
            'request' => $requestId,
            'supplier' => $supplierId,
            'value' => 9.30,
        ]);
    }

    public function down()
    {
        $this->dropForeignKey('fk_quotation_supplier_id', '{{%quotation}}');
        $this->dropForeignKey('fk_quotation_request_id', '{{%quotation}}');
        $this->dropTable('{{%quotation}}');
    }

}
