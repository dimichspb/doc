<?php

use yii\db\Migration;

class m160329_105605_adding_foreignkeys_entity_role_person extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_entity_person_role_entity_id', '{{%entity_person_role}}', 'entity', '{{%entity}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_entity_person_role_role_id', '{{%entity_person_role}}', 'role', '{{%entity_role}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_entity_person_role_person_id', '{{%entity_person_role}}', 'person', '{{%person}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_entity_person_role_person_id', '{{%entity_person_role}}');
        $this->dropForeignKey('fk_entity_person_role_role_id', '{{%entity_person_role}}');
        $this->dropForeignKey('fk_entity_person_role_entity_id', '{{%entity_person_role}}');
    }

}
