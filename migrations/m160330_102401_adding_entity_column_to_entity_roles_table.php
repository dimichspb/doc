<?php

use yii\db\Migration;

class m160330_102401_adding_entity_column_to_entity_roles_table extends Migration
{
    public function up()
    {
        $entityId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%entity}}')
            ->min('id');

        $this->addColumn('{{%entity_role}}', 'entity', $this->integer(11));
        $this->update('{{%entity_role}}', ['entity' => $entityId]);
        $this->addForeignKey('fk_entity_role_entity_id', '{{%entity_role}}', 'entity', '{{%entity}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_entity_role_entity_id', '{{%entity_role}}');
        $this->dropColumn('{{%entity_role}}', 'entity');
    }

}
