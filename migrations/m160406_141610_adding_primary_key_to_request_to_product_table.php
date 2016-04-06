<?php

use yii\db\Migration;

class m160406_141610_adding_primary_key_to_request_to_product_table extends Migration
{
    public function up()
    {
        $this->addPrimaryKey('pk_request_to_product', '{{%request_to_product}}', ['request', 'product']);
    }

    public function down()
    {
        $this->dropPrimaryKey('pk_request_to_product', '{{%request_to_product}}');
    }

}
