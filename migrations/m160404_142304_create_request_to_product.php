<?php

use yii\db\Migration;
use yii\db\Query;

class m160404_142304_create_request_to_product extends Migration
{
    public function up()
    {
        $this->createTable('{{%request_to_product}}', [
            'request' => $this->integer(11),
            'product' => $this->integer(11),
            'quantity' => $this->integer(11)->notNull()->defaultValue(0),
        ]);

        $this->addForeignKey('fk_request_to_product_request_id', '{{%request_to_product}}', 'request', '{{%request}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_request_to_product_product_id', '{{%request_to_product}}', 'product', '{{%product}}', 'id', 'RESTRICT', 'CASCADE');

        $this->dropForeignKey('fk_request_product_id', '{{%request}}');
        $this->dropColumn('{{%request}}', 'product');
        $this->dropColumn('{{%request}}', 'quantity');
    }

    public function down()
    {
        $productId = (new Query)
            ->select('id')
            ->from('{{%product}}')
            ->min('id');

        $this->dropTable('{{%request_to_product}}');
        $this->addColumn('{{%request}}', 'product', $this->integer(11)->notNull()->defaultValue($productId));
        $this->addColumn('{{%request}}', 'quantity', $this->integer(11)->notNull()->defaultValue(0));

        $this->addForeignKey('fk_request_product_id', '{{%request}}', 'product', '{{%product}}', 'id', 'RESTRICT', 'CASCADE');
    }
}
