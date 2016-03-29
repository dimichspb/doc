<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entity_person_role".
 *
 * @property integer $id
 * @property integer $entity
 * @property integer $role
 * @property integer $person
 *
 * @property Entity[] $entities
 * @property Entity[] $entities0
 */
class EntityPersonRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity_person_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity', 'role', 'person'], 'required'],
            [['entity', 'role', 'person'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity' => 'Entity',
            'role' => 'Role',
            'person' => 'Person',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntities()
    {
        return $this->hasMany(Entity::className(), ['accountant' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntities0()
    {
        return $this->hasMany(Entity::className(), ['director' => 'id']);
    }
}
