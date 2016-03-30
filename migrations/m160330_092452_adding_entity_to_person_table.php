<?php

use yii\db\Migration;

class m160330_092452_adding_entity_to_person_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%entity_to_person}}', [
            'entity' => $this->integer(11),
            'person' => $this->integer(11),
        ], "DEFAULT CHARSET=utf8");

        $this->addForeignKey('fk_entity_to_person_entity_id', '{{%entity_to_person}}', 'entity', '{{%entity}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_entity_to_person_person_id', '{{%entity_to_person}}', 'person', '{{%person}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%entity_to_person}}');
    }
}
