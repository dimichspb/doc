<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entity_form".
 *
 * @property integer $id
 * @property string $name
 * @property string $fullname
 *
 * @property Entity[] $entities
 */
class EntityForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity_form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'fullname'], 'required'],
            [['name'], 'string', 'max' => 4],
            [['fullname'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'fullname' => 'Fullname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntities()
    {
        return $this->hasMany(Entity::className(), ['entity_form' => 'id']);
    }
}
