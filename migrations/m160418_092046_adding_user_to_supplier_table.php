<?php

use yii\db\Migration;

class m160418_092046_adding_user_to_supplier_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%user_to_supplier}}', [
            'user' => $this->integer(11),
            'supplier' => $this->integer(11),
        ]);
        
        $this->addPrimaryKey('pk_user_to_supplier', '{{%user_to_supplier}}', ['user', 'supplier']);
        $this->addForeignKey('fk_user_to_supplier_user_id', '{{%user_to_supplier}}', 'user', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_to_supplier_supplier_id', '{{%user_to_supplier}}', 'supplier', '{{%supplier}}', 'id', 'CASCADE', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropTable('{{%user_to_supplier}}');
    }
}
