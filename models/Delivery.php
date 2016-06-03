<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "delivery".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $delivered_at
 * @property integer $order
 * @property integer $shipper
 *
 * @property Order $order0
 * @property Shipper $shipper0
 * @property DeliveryToProduct[] $deliveryToProducts
 * @property Product[] $products
 */
class Delivery extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;
    const STATUS_SHIPPED = 11;
    const STATUS_DELIVERED = 12;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'delivered_at', 'order', 'shipper'], 'integer'],
            [['order', 'shipper'], 'required'],
            [['order'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order' => 'id']],
            [['shipper'], 'exist', 'skipOnError' => true, 'targetClass' => Shipper::className(), 'targetAttribute' => ['shipper' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер',
            'status' => 'Статус',
            'created_at' => 'Создана',
            'updated_at' => 'Изменена',
            'delivered_at' => 'Завершена',
            'order' => 'Заказ',
            'shipper' => 'Перевозчик',
        ];
    }

    /**
    * @return array
    */
    public static function getStatusArray()
    {
        $statusArray = [
            Delivery::STATUS_ACTIVE => 'Новая',
            Delivery::STATUS_SHIPPED => 'Отгружена',
            Delivery::STATUS_DELIVERED => 'Доставлена',
            Delivery::STATUS_INACTIVE => 'Неактивна',
            Delivery::STATUS_DELETED => 'Удалена',
        ];
        return $statusArray;
    }
    
    /**
    * @return string 
    */
    public function getStatusName()
    {
        $statusArray = Delivery::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
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
        return $this->getOrder()->exists()? $this->getOrder()->one(): null;
    }
    
    public function getOrderName()
    {
        return $this->getOrderOne()? $this->getOrderOne()->getName(): '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipper()
    {
        return $this->hasOne(Shipper::className(), ['id' => 'shipper']);
    }
    
    /**
    * @return Shipper 
    */
    public function getShipperOne()
    {
        return $this->getShipper()->exists()? $this->getShipper()->one(): '';
    }
    
    public function getShipperName()
    {
        return $this->getShipper()->exists()? $this->getShipperOne()->getName(): '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryToProducts()
    {
        return $this->hasMany(DeliveryToProduct::className(), ['delivery' => 'id']);
    }
    
    /**
     * @param integer|null $id
     * @return DeliveryToProduct[]
     */
    public function getDeliveryToProductsAll($id = null)
    {
        if (is_null($id)) {
            return $this->getDeliveryToProducts()->all();
        }
        $orderToProducts = $this->getOrderToProductsAll($id);
        $deliveryToProducts = [];
        foreach ($orderToProducts as $orderToProduct) {
            $deliveryToProduct = new DeliveryToProduct();
            $deliveryToProduct->product = $orderToProduct->product;
            $deliveryToProduct->quantity = $orderToProduct->quantity;
            $deliveryToProducts[] = $deliveryToProduct;
        }
        return $deliveryToProducts;
    }
    
        /**
     * @param integer|null $id
     * @return ActiveQuery
     */
    public function getOrderToProducts($id = null)
    {
        if (!is_null($id)) {
            $order = Order::findOne(['id' => $id]);
            return $order->getOrderToProducts();
        }

        if (isset($this->order)) {
            return $this->getOrderOne()->getOrderToProducts();
        }
    }
    
    /**
     * @param integer|null $id
     * @return OrderToProduct[]
     */
    public function getOrderToProductsAll($id = null)
    {
        return $this->getOrderToProducts($id)->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product'])->viaTable('delivery_to_product', ['delivery' => 'id']);
    }
    
    /**
    * @return Product[]
    */
    public function getProductsAll()
    {
        return $this->getProducts()->exists()? $this->getProducts()->all(): [];
    }
    
    public function beforeSave($insert)
    {
        $today = new \DateTime();
        $this->updated_at = $today->getTimestamp();

        if ($insert) {
            $this->created_at = $today->getTimestamp();
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
    
    public function getName()
    {
        return implode(' - ', [
            $this->id,
            $this->getOrderName(),
            $this->getShipperName(),  
        ]);
    }
}
