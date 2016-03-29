<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank".
 *
 * @property integer $id
 * @property integer $entity_form
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
            'entity_form' => 'Entity Form',
            'name' => 'Name',
            'code' => 'Code',
            'account' => 'Account',
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
