<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entity_role".
 *
 * @property integer $id
 * @property string $name
 * @property Entity $entity
 */
class EntityRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['entity'], 'integer'],
            [['entity'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity' => 'Юр. лицо',
            'name' => 'Наименование',
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Entity::className(), ['id' => 'entity']);
    }

    /**
     * @return Entity
     */
    public function getEntityOne()
    {
        return $this->getEntity()->one();
    }

    /**
     * @return string
     */
    public function getEntityFull()
    {
        return $this->getEntityOne()->getFull();
    }
}
