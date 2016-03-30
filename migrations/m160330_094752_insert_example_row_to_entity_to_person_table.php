<?php

use yii\db\Migration;

class m160330_094752_insert_example_row_to_entity_to_person_table extends Migration
{
    public function up()
    {
        $entityId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%entity}}')
            ->min('id');

        $personId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%person}}')
            ->min('id');

        $this->insert('{{entity_to_person}}', [
            'entity' => $entityId,
            'person' => $personId,
        ]);
    }

    public function down()
    {

    }

}
