<?php

use yii\db\Migration;

class m160329_114147_adding_entity_column_to_account_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%account}}', 'entity', $this->integer(11));
        $this->addForeignKey('fk_account_entity_id', '{{%account}}', 'entity', '{{%entity}}', 'id', 'CASCADE', 'CASCADE');

        $entityId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%entity}}')
            ->min('id');

        $accountId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%account}}')
            ->max('id');

        $this->update('{{%account}}', ['entity' => $entityId], ['id' => $accountId]);
    }

    public function down()
    {
        $this->dropForeignKey('fk_account_entity_id', '{{%account}}');
        $this->dropColumn('{{%account}}', 'entity');
    }
}
