<?php

use yii\db\Migration;
use app\models\Order;

class m160604_135055_adding_seller_entity_column_to_order_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'seller', $this->integer(11));
        $defaultSeller = Order::getDefaultSeller();
        $this->update('{{%order}}', [
            'seller' => $defaultSeller->id,
        ], [
            'seller' => null,
        ]);
        $this->addForeignKey('fk_order_seller_entity_id', '{{%order}}', 'seller', '{{%entity}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_order_seller_entity_id', '{{%order}}');
        $this->dropColumn('{{%order}}', 'seller');
    }
}
