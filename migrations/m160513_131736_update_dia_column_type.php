<?php

use yii\db\Migration;

class m160513_131736_update_dia_column_type extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%product}}', 'dia', $this->float());
        $this->addColumn('{{%product}}', 'length', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%product}}', 'length');
        $this->alterColumn('{{%product}}', 'dia', $this->integer(3));
    }

}
