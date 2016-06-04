<?php

use yii\db\Migration;
use app\models\Entity;

class m160604_134455_adding_config_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%config}}', [
            'id' => $this->primaryKey(11),
            'key' => $this->string(64),
            'value' => $this->string(64),
        ]);
        $this->insert('{{%config}}', [
            'key' => 'defaultOrderEntity',
            'value' => Entity::findByInn('7813223912')->id,
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%config}}');
    }

}
