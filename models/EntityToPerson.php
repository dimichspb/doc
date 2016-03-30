<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entity_to_person".
 *
 * @property integer $entity
 * @property integer $person
 *
 * @property Entity $entity0
 * @property Person $person0
 */
class EntityToPerson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity_to_person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity', 'person'], 'integer'],
            [['entity'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::className(), 'targetAttribute' => ['entity' => 'id']],
            [['person'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entity' => 'Entity',
            'person' => 'Person',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity0()
    {
        return $this->hasOne(Entity::className(), ['id' => 'entity']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson0()
    {
        return $this->hasOne(Person::className(), ['id' => 'person']);
    }
}
