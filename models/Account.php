<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "account".
 *
 * @property integer $id
 * @property string $number
 *
 * @property Bank $bank
 * @property Bank[] $banks
 * @property Entity[] $entities
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank', 'number'], 'required'],
            [['bank'], 'integer'],
            [['number'], 'string', 'max' => 20],
            [['bank'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bank' => 'Банк',
            'number' => 'Номер счета',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank']);
    }

    /**
     * @return Bank
     */
    public function getBankOne()
    {
        return $this->getBank()->one();
    }

    public function getBankName()
    {
        return $this->getBankOne()->getFull();
    }

    public function getBankCode()
    {
        return $this->getBankOne()->getCode();
    }

    public function getBankAccount()
    {
        return $this->getBankOne()->getAccountNumber();
    }

    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanks()
    {
        return $this->hasMany(Bank::className(), ['account' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntities()
    {
        return $this->hasMany(Entity::className(), ['account' => 'id']);
    }

    /**
     * @return string
     */
    public function getFull()
    {
        return
            $this->getBankName() . ', ' .
            $this->number;
    }

    /**
     * @return array
     */
    public static function getAccountArray()
    {
        return ArrayHelper::map(Account::find()->all(), 'id', 'full');
    }

}
