<?php

use yii\db\Migration;

class m160325_123029_adding_product_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(11),
            'status' => $this->integer(2)->notNull()->defaultValue(10),
            'code' => $this->string(32)->notNull(),
            'name' => $this->string(255)->notNull(),
            'material' => $this->integer(11)->notNull(),
            'dia' => $this->integer(3),
            'thread' => $this->integer(3),
            'package' => $this->integer(6),
        ], "DEFAULT CHARSET=utf8");

        $this->createTable('{{%material}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(255)->notNull(),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('product_material_id', '{{%product}}', 'material', '{{%material}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%product}}');
        $this->dropTable('{{%material}}');
    }

}
