<?php

use yii\db\Migration;

class m160418_092038_adding_user_to_customer_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%user_to_customer}}', [
            'user' => $this->integer(11),
            'customer' => $this->integer(11),
        ]);
        
        $this->addPrimaryKey('pk_user_to_customer', '{{%user_to_customer}}', ['user', 'customer']);
        $this->addForeignKey('fk_user_to_customer_user_id', '{{%user_to_customer}}', 'user', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_to_customer_customer_id', '{{%user_to_customer}}', 'customer', '{{%customer}}', 'id', 'CASCADE', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropTable('{{%user_to_customer}}');
    }
}
