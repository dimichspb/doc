<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property integer $status
 * @property string $name
 *
 * @property Request[] $requests
 */
class Customer extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
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
            'status' => 'Status',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['customer' => 'id']);
    }

    /**
     * @return Customer[]
     */
    public static function getActiveAll()
    {
        return Customer::find()->where(['status' => Customer::STATUS_ACTIVE])->all();
    }

    public function getStatusName()
    {
        $statusArray = Customer::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
    }

    public static function getStatusArray()
    {
        $statusArray = [
            Customer::STATUS_ACTIVE => 'Активен',
            Customer::STATUS_INACTIVE => 'Неактивен',
            Customer::STATUS_DELETED => 'Удален',
        ];
        return $statusArray;
    }
}