<?php

use yii\db\Migration;

class m160414_105603_adding_primary_key_to_quotation_to_product_table extends Migration
{
    public function up()
    {
        $this->addPrimaryKey('pk_quotation_to_product', '{{%quotation_to_product}}', ['quotation', 'product']);
    }

    public function down()
    {
        $this->dropPrimaryKey('pk_quotation_to_product', '{{%quotation_to_product}}');
    }
}
