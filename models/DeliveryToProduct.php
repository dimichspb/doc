<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "delivery_to_product".
 *
 * @property integer $delivery
 * @property integer $product
 * @property integer $quantity
 *
 * @property Delivery $delivery0
 * @property Product $product0
 */
class DeliveryToProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_to_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery', 'product'], 'required'],
            [['delivery', 'product', 'quantity'], 'integer'],
            [['delivery'], 'exist', 'skipOnError' => true, 'targetClass' => Delivery::className(), 'targetAttribute' => ['delivery' => 'id']],
            [['product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'delivery' => 'Отгрузка',
            'product' => 'Товар',
            'quantity' => 'Количество',
            'price' => 'Цена',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['id' => 'delivery']);
    }
    
    public function getDeliveryOne()
    {
        return $this->getDelivery()->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product']);
    }
    
    public function getProductOne()
    {
        return $this->getProduct()->one();
    }
}
