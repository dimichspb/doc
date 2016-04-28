<?php

use yii\db\Migration;

class m160428_064324_adding_menuitem_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%menu_item}}', [
            'id' => $this->primaryKey(11),
            'parent' => $this->integer(11),
            'icon' => $this->string(255),
            'label' => $this->string(255),
            'action' => $this->string(255),
        ]);
        $this->addForeignKey('fk_menu_item_parent_id', '{{%menu_item}}', 'parent', '{{%menu_item}}', 'id', 'RESTRICT', 'RESTRICT');
        
        $this->createTable('{{%menu_item_to_role}}', [
            'menu_item' => $this->integer(11),
            'role' => $this->string(64),
        ]);
        
        $this->addForeignKey('fk_menu_item_to_role_menu_item_id', '{{%menu_item_to_role}}', 'menu_item', '{{%menu_item}}', 'id' , 'CASCADE', 'CASCADE');
}

    public function down()
    {
        $this->dropTable('{{%menu_item_to_role}}');
        $this->dropTable('{{%menu_item}}');
    }

}
