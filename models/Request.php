<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user
 * @property integer $product
 * @property integer $quantity
 *
 * @property Quotation[] $quotations
 * @property Product $product0
 * @property User $user0
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'user', 'product', 'quantity'], 'integer'],
            [['created_at', 'updated_at', 'user', 'product'], 'required'],
            [['product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product' => 'id']],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user' => 'User',
            'product' => 'Product',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotations()
    {
        return $this->hasMany(Quotation::className(), ['request' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct0()
    {
        return $this->hasOne(Product::className(), ['id' => 'product']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
}
