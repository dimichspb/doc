<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "supplier".
 *
 * @property integer $id
 * @property integer $status
 * @property string $name
 *
 * @property Price[] $prices
 */
class Supplier extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'name' => 'Наименование',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['supplier' => 'id']);
    }

    public function getStatusName()
    {
        $statusArray = Supplier::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
    }

    public static function getStatusArray()
    {
        $statusArray = [
            Supplier::STATUS_ACTIVE => 'Активен',
            Supplier::STATUS_INACTIVE => 'Неактивен',
            Supplier::STATUS_DELETED => 'Удален',
        ];
        return $statusArray;
    }
    
    /**
    * @return ActiveQuery
    */
    public static function getActive()
    {
        return Supplier::find()->where(['status' => Supplier::STATUS_ACTIVE]);
    }

    /**
     * @return Supplier[]
     */
    public static function getActiveAll()
    {
        return Supplier::getActive()->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierToEntities()
    {
        return $this->hasMany(SupplierToEntity::className(), ['supplier' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEntities()
    {
        return $this->hasMany(Entity::className(), ['id' => 'entity'])->via('supplierToEntities');
    }

    /**
     * @return Entity[]
     */
    public function getEntitiesAll()
    {
        return $this->getEntities()->all();
    }
    
    public function getSupplierToUsers()
    {
        return $this->hasMany(UserToSupplier::className(), ['supplier' => 'id']);
    }
    
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user'])->via('supplierToUsers');
    }
    
    public function getUsersAll()
    {
        return $this->getUsers()->all();
    }
}
