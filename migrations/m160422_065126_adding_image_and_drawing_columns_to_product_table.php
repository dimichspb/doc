<?php

use yii\db\Migration;

class m160422_065126_adding_image_and_drawing_columns_to_product_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%product}}', 'image_file', $this->string());
        $this->addColumn('{{%product}}', 'drawing_file', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%product}}', 'image_file');
        $this->dropColumn('{{%product}}', 'drawing_file');
    }
}
