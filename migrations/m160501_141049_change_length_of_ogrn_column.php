<?php

use yii\db\Migration;

class m160501_141049_change_length_of_ogrn_column extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%entity}}', 'ogrn', $this->string(15)->unique());
    }

    public function down()
    {
        $this->alterColumn('{{%entity}}', 'ogrn', $this->string(13)->unique());
    }

}
