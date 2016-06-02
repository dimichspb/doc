<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $expire_at
 * @property integer $quotation
 *
 * @property OrderToProduct[] $orderToProducts
 */
class Order extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;
    const STATUS_PAID = 11;
    const STATUS_SHIPPED = 12;
    const STATUS_DELIVERED = 13;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'expire_at', 'quotation'], 'integer'],
            [['quotation'], 'required'],
            [['quotation'], 'exist', 'skipOnError' => true, 'targetClass' => Quotation::className(), 'targetAttribute' => ['quotation' => 'id']],
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
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
            'expire_at' => 'Действует до',
            'request' => 'Запрос',
            'quotation' => 'Предложение',
            'amount' => 'Сумма',
            'paidAmount' => 'Оплачено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotation()
    {
        return $this->hasOne(Quotation::className(), ['id' => 'quotation']);
    }
    
    /**
    * @return Quotation 
    */
    public function getQuotationOne()
    {
        return $this->getQuotation()->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderToProducts()
    {
        return $this->hasMany(OrderToProduct::className(), ['order' => 'id']);
    }
    
    
    /**
     * @param integer|null $id
     * @return OrderToProduct[]
     */
    public function getOrderToProductsAll($id = null)
    {
        if (is_null($id)) {
            return $this->getOrderToProducts()->all();
        }
        $quotationToProducts = $this->getQuotationToProductsAll($id);
        $orderToProducts = [];
        foreach ($quotationToProducts as $quotationToProduct) {
            $orderToProduct = new OrderToProduct();
            $orderToProduct->product = $quotationToProduct->product;
            $orderToProduct->quantity = $quotationToProduct->quantity;
            $orderToProduct->price = $quotationToProduct->price;
            $orderToProducts[] = $orderToProduct;
        }
        return $orderToProducts;
    }

    /**
     * @param integer|null $id
     * @return ActiveQuery
     */
    public function getQuotationToProducts($id = null)
    {
        if (!is_null($id)) {
            $quotation = Quotation::findOne(['id' => $id]);
            return $quotation->getQuotationToProducts();
        }

        if (isset($this->quotation)) {
            return $this->getQuotationOne()->getQuotationToProducts();
        }
    }
    
    /**
     * @param integer|null $id
     * @return QuotationToProduct[]
     */
    public function getQuotationToProductsAll($id = null)
    {
        return $this->getQuotationToProducts($id)->all();
    }
    
    /**
    * @return array
    */
    public static function getStatusArray()
    {
        $statusArray = [
            Order::STATUS_ACTIVE => 'Новый',
            Order::STATUS_PAID => 'Оплачен',
            Order::STATUS_SHIPPED => 'Отгружен',
            Order::STATUS_DELIVERED => 'Доставлен',
            Order::STATUS_INACTIVE => 'Неактивен',
            Order::STATUS_DELETED => 'Удален',
        ];
        return $statusArray;
    }
    
    /**
    * @return string 
    */
    public function getStatusName()
    {
        $statusArray = Order::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
    }
    
    /**
    * @return string
    */
    public function getQuotationName()
    {
        return $this->getQuotationOne()->getName();
    }
    
    public function getName()
    {
        return implode(' - ', [
            $this->id,
            $this->getQuotationName(),
        ]);
    }
    
    /**
     * @return Order|void
     */
    public static function getFirst()
    {
        $firstActiveOrder = Order::getActive()->one();
        if ($firstActiveOrder) {
            return $firstActiveOrder;
        }
        $firstPaidOrder = Order::getPaid()->one();
        if ($firstPaidOrder) {
            return $firstPaidOrder;
        }
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
    
    
    public static function getActive()
    {
        return Order::find()->where(['status' => Order::STATUS_ACTIVE]); 
    }
    
    /**
    * @return Order[]
    */
    public static function getActiveAll()
    {
        return Order::getActive()->all();
    }
    
    /**
    * @return ActiveQuery
    */
    public static function getActiveAndPaid()
    {
        return Order::find()->where(['status' => Order::STATUS_ACTIVE])->orWhere(['status' => Order::STATUS_PAID]);
    }
    
    /**
    * @return Order[]
    */
    public static function getActiveAndPaidAll()
    {
        return Order::getActiveAndPaid()->all();
    }
    
    /**
    * @return ActiveQuery
    */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['order' => 'id']);
    }
    
    /**
    * @return Payment[]
    */
    public function getPaymentsAll()
    {
        return $this->getPayments()->all();
    }
    
    /**
    * @return ActiveQuery
    */
    public function getActivePayments()
    {
        return $this->getPayments()->where(['status' => Payment::STATUS_ACTIVE]);
    }
    
    /**
    * @return Payment[]
    */
    public function getActivePaymentsAll()
    {
        return $this->getActivePayments()->all();
    }
    
    public function getAmount()
    {
        $amount = 0;
        $orderToProducts = $this->getOrderToProductsAll();
        foreach ($orderToProducts as $orderToProduct) {
            $amount = $amount + round($orderToProduct->price * $orderToProduct->quantity, 2);    
        }
        return $amount;   
    }
    
    public function getPaidAmount()
    {
        $amount = 0;
        $payments = $this->getActivePaymentsAll();
        foreach ($payments as $payment) {
            $amount = $amount + round($payment->amount, 2);
        }
        return $amount;
    }
    
    
    /**
     * @return ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product'])->viaTable('{{%order_to_product}}', ['order' => 'id']);
    }

    /**
     * @return Product[]
     */
    public function getProductsAll()
    {
        return $this->getProducts()->all();
    }
}
