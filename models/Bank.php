<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bank".
 *
 * @property integer $id
 * @property EntityForm $entity_form
 * @property string $name
 * @property string $code
 *
 * @property Account[] $accounts
 * @property Account $account
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_form', 'name', 'code'], 'required'],
            [['entity_form', 'account'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 9],
            [['account'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_form' => 'Правовая форма',
            'name' => 'Наименование',
            'code' => 'БИК',
            'account' => 'Кор. счет',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['bank' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account']);
    }

    /**
     * @return Account
     */
    public function getAccountOne()
    {
        return $this->getAccount()->exists() ? $this->getAccount()->one() : null;
    }

    public function getAccountNumber()
    {
        return $this->getAccountOne() ? $this->getAccountOne()->getNumber() : '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntityForm()
    {
        return $this->hasOne(EntityForm::className(), ['id' => 'entity_form']);
    }

    /**
     * @return EntityForm
     */
    public function getEntityFormOne()
    {
        return $this->getEntityForm()->exists() ? $this->getEntityForm()->one() : null;
    }

    public function getEntityFormName()
    {
        return $this->getEntityFormOne() ? $this->getEntityFormOne()->getName() : '';
    }

    /**
     * @return string
     */
    public function getFull()
    {
        return $this->getEntityFormName() . ' ' . $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public static function getBankArray()
    {
        return ArrayHelper::map(Bank::find()->all(), 'id', 'full');
    }

    public static function findByCode($code)
    {
        return Bank::find()->where(['code' => $code])->one();
    }
}
