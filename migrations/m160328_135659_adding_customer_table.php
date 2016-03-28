<?php

use yii\db\Migration;

class m160328_135659_adding_customer_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%customer}}', [
            'id' => $this->primaryKey(11),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'name' => $this->string(255),
        ], "DEFAULT CHARSET=utf8");

        $this->insert('{{%customer}}', [
            'name' => 'Первый клиент',
        ]);

        $today = new \DateTime();
        $customerId = $this->db->lastInsertID;
        $product1Id = (new \yii\db\Query())
            ->select('id')
            ->from('{{%product}}')
            ->min('id');
        $product2Id = (new \yii\db\Query())
            ->select('id')
            ->from('{{%product}}')
            ->max('id');

        $this->delete('{{%request}}');
        $this->dropForeignKey('fk_request_user_id', '{{%request}}');
        $this->renameColumn('{{%request}}', 'user', 'customer');
        $this->addForeignKey('fk_request_customer_id', '{{%request}}', 'customer', '{{%customer}}', 'id', 'RESTRICT', 'CASCADE');

        $this->batchInsert('{{%request}}', [
            'created_at', 'updated_at', 'customer', 'product', 'quantity'
        ], [
            [$today->getTimestamp(), $today->getTimestamp(), $customerId, $product1Id, '1000'],
            [$today->getTimestamp(), $today->getTimestamp(), $customerId, $product2Id, '1000'],
        ]);

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
        $this->dropForeignKey('fk_request_customer_id', '{{%request}}');
        $this->renameColumn('{{%request}}', 'customer', 'user');
        $this->addForeignKey('fk_request_user_id', '{{%request}}', 'user', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->dropTable('{{%customer}}');
    }

}
