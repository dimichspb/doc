<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_to_product".
 *
 * @property integer $order
 * @property integer $product
 * @property integer $quantity
 * @property double $price
 *
 */
class OrderToProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_to_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order', 'product', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['order'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order' => 'id']],
            [['product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order' => 'Заказ',
            'product' => 'Товар',
            'quantity' => 'Количество',
            'price' => 'Цена',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order']);
    }
    
    /**
    * @return Order 
    */
    public function getOrderOne()
    {
        return $this->getOrder()->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product']);
    }
    
    /**
    * @return Product 
    */
    public function getProductOne()
    {
        return $this->getProduct()->one();
    }
}
