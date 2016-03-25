<?php

use yii\db\Migration;

class m160325_125228_adding_material_record extends Migration
{
    public function up()
    {
        $this->insert('{{%material}}', [
            'name' => 'СТАЛЬ ЦИНК',
        ]);
    }

    public function down()
    {
        $this->delete('{{%material}}', [
            'name' => 'СТАЛЬ ЦИНК',
        ]);
    }

}
