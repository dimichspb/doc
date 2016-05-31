<?php

use yii\db\Migration;

class m160328_132451_adding_request_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%request}}', [
            'id' => $this->primaryKey(11),
            'status' => $this->integer(2)->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'user' => $this->integer(11)->notNull(),
            'product' => $this->integer(11)->notNull(),
            'quantity' => $this->integer(11)->notNull()->defaultValue(0),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('fk_request_user_id', '{{%request}}', 'user', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_request_product_id', '{{%request}}', 'product', '{{%product}}', 'id', 'CASCADE', 'CASCADE');


        $today = new \DateTime();
        $userId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%user}}')
            ->min('id');
        $product1Id = (new \yii\db\Query())
            ->select('id')
            ->from('{{%product}}')
            ->min('id');
        $product2Id = (new \yii\db\Query())
            ->select('id')
            ->from('{{%product}}')
            ->max('id');

        $this->batchInsert('{{%request}}', [
                'created_at', 'updated_at', 'user', 'product', 'quantity'
            ], [
                [$today->getTimestamp(), $today->getTimestamp(), $userId, $product1Id, '1000'],
                [$today->getTimestamp(), $today->getTimestamp(), $userId, $product2Id, '1000'],
            ]);

    }

    public function down()
    {
        $this->dropForeignKey('fk_request_product_id', '{{%request}}');
        $this->dropForeignKey('fk_request_user_id', '{{%request}}');
        $this->dropTable('{{%request}}');
    }
}
