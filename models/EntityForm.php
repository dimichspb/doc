<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

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

    /**
     * @return array
     */
    public static function getEntityFormsArray()
    {
        return ArrayHelper::map(EntityForm::find()->all(), 'id', 'name');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $shortName
     * @return EntityForm
     */
    public static function findByShortName($shortName)
    {
        return EntityForm::find()->where(['name' => $shortName])->one();
    }
    
    public static function findByFullName($fullName)
    {
        return EntityForm::find()->where(['fullname' => $fullName])->one();
    }
}
