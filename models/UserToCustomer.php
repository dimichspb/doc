<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_to_customer".
 *
 * @property integer $user
 * @property integer $customer
 *
 * @property Customer $customer0
 * @property User $user0
 */
class UserToCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_to_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'customer'], 'required'],
            [['user', 'customer'], 'integer'],
            [['customer'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer' => 'id']],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user' => 'User',
            'customer' => 'Customer',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer0()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
}
