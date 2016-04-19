<?php

use yii\db\Migration;

class m160419_090027_adding_shipper_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%shipper}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(255)->notNull(),
        ], "DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->dropTable('{{%shipper}}');
    }
}
