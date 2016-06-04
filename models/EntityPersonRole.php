<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "entity_person_role".
 *
 * @property integer $id
 *
 * @property Entity[] $entities
 * @property Entity $entity
 * @property Person $person
 * @property EntityRole $role
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
            [['entity'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::className(), 'targetAttribute' => ['entity' => 'id']],
            [['person'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person' => 'id']],
            [['role'], 'exist', 'skipOnError' => true, 'targetClass' => EntityRole::className(), 'targetAttribute' => ['role' => 'id']],
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
            'role' => 'Должность',
            'person' => 'Человек',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntitiesByAccountant()
    {
        return $this->hasMany(Entity::className(), ['accountant' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntitiesByDirector()
    {
        return $this->hasMany(Entity::className(), ['director' => 'id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person']);
    }

    /**
     * @return Person
     */
    public function getPersonOne()
    {
        return $this->getPerson()->one();
    }

    /**
     * @return string
     */
    public function getPersonFullname()
    {
        return $this->getPersonOne()->getFullname();
    }

    public function getPersonShortname()
    {
        return $this->getPersonOne()->getShortname();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(EntityRole::className(), ['id' => 'role']);
    }

    /**
     * @return EntityRole
     */
    public function getRoleOne()
    {
        return $this->getRole()->one();
    }

    /**
     * @return string
     */
    public function getRoleName()
    {
        return $this->getRoleOne()->getName();
    }

    /**
     * @return string
     */
    public function getFull()
    {
        return $this->getRoleName() . ' ' . $this->getPersonFullname();
    }

    public function getShort()
    {
        return $this->getRoleName() . ' ' . $this->getPersonShortname();
    }

    /**
     * @return array
     */
    public static function getEntityPersonRoleArray()
    {
        return ArrayHelper::map(EntityPersonRole::find()->all(), 'id', 'full');
    }
}
