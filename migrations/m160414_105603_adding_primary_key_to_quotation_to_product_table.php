<?php

use yii\db\Migration;

class m160414_105603_adding_primary_key_to_quotation_to_product_table extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_quotation_to_product_quotation_id', '{{%quotation_to_product}}');
        $this->dropForeignKey('fk_quotation_to_product_product_id', '{{%quotation_to_product}}');

        $this->addPrimaryKey('pk_quotation_to_product', '{{%quotation_to_product}}', ['quotation', 'product']);

        $this->addForeignKey('fk_quotation_to_product_quotation_id', '{{%quotation_to_product}}', 'quotation', '{{%quotation}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_quotation_to_product_product_id', '{{%quotation_to_product}}', 'product', '{{%product}}', 'id', 'RESTRICT', 'CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('fk_quotation_to_product_quotation_id', '{{%quotation_to_product}}');
        $this->dropForeignKey('fk_quotation_to_product_product_id', '{{%quotation_to_product}}');

        $this->dropPrimaryKey('pk_quotation_to_product', '{{%quotation_to_product}}');

        $this->addForeignKey('fk_quotation_to_product_quotation_id', '{{%quotation_to_product}}', 'quotation', '{{%quotation}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_quotation_to_product_product_id', '{{%quotation_to_product}}', 'product', '{{%product}}', 'id', 'RESTRICT', 'CASCADE');

    }
}
