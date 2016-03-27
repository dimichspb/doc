<?php

use yii\db\Migration;

class m160325_125228_adding_material_record extends Migration
{
    public function up()
    {
        $this->insert('{{%material}}', [
            'name' => 'Сталь цинк',
        ]);
    }

    public function down()
    {
        $this->delete('{{%material}}', [
            'name' => 'Сталь цинк',
        ]);
    }

}
