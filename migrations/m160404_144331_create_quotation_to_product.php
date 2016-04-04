<?php

use yii\db\Migration;
use yii\db\Query;

class m160404_144331_create_quotation_to_product extends Migration
{
    public function up()
    {
        $this->createTable('{{%quotation_to_product}}', [
            'quotation' => $this->integer(11),
            'product' => $this->integer(11),
            'quantity' => $this->integer(11)->notNull()->defaultValue(0),
            'price' => $this->float()->notNull()->defaultValue(0),
        ]);

        $this->addForeignKey('fk_quotation_to_product_quotation_id', '{{%quotation_to_product}}', 'quotation', '{{%quotation}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_quotation_to_product_product_id', '{{%quotation_to_product}}', 'product', '{{%product}}', 'id', 'RESTRICT', 'CASCADE');

        $this->dropColumn('{{%quotation}}', 'value');
    }

    public function down()
    {
        $this->dropTable('{{%quotation_to_product}}');
        $this->addColumn('{{%quotation}}', 'value', $this->integer(11)->notNull()->defaultValue(0));

    }
}
