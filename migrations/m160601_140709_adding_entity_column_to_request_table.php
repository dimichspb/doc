<?php

use yii\db\Migration;
use app\models\Request;

class m160601_140709_adding_entity_column_to_request_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%request}}', 'entity', $this->integer(11));
        $requests = Request::find()->all();
        foreach($requests as $request) {
            $customer = $request->getCustomerOne();
            $entities = $customer->getEntities();
            if ($entities->exists()) {
                $firstEntity = $entities->one();
                $request->entity = $firstEntity->id;
                $request->save();
            }
        }
        $requests = Request::find()->where(new \yii\db\Expression('`entity` IS NULL'))->all();
        foreach ($requests as $request) {
            $request->delete();
        }
        $this->addForeignKey('fk_request_entity', '{{%request}}', 'entity', '{{%entity}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_request_entity', '{{%request}}');
        $this->dropColumn('{{%request}}', 'entity');
    }

}
