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
 * @property integer $quantity
 * @property Quotation[] $quotations
 * @property Product $product
 * @property Customer $customer
 */
class Request extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;

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
            [['status', 'updated_at', 'customer', 'product', 'quantity'], 'integer'],
            [['created_at'], 'integer'],
            [['customer', 'product'], 'required'],
            [['product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product' => 'id']],
            [['customer'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer' => 'id']],
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
            'customer' => 'Клиент',
            'product' => 'Товар',
            'quantity' => 'Количество',
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product']);
    }

    public function getProductOne()
    {
        return $this->getProduct()->one();
    }

    public function getProductFullname()
    {
        return $this->getProductOne()->fullname;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer']);
    }

    /**
     * @return Customer
     */
    public function getCustomerOne()
    {
        return $this->getCustomer()->one();
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->getCustomerOne()->name;
    }

    public function getStatusName()
    {
        $statusArray = Request::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
    }

    public static function getStatusArray()
    {
        $statusArray = [
            Request::STATUS_ACTIVE => 'Активен',
            Request::STATUS_INACTIVE => 'Неактивен',
            Request::STATUS_DELETED => 'Удален',
        ];
        return $statusArray;
    }

    public function beforeSave($insert)
    {
        $today = new \DateTime();

        $this->updated_at = $today->getTimestamp();

        if ($insert) $this->created_at = $today->getTimestamp();

        return parent::beforeSave($insert);
    }

    public function getName()
    {
        return
            $this->id . ' - ' .
            Yii::$app->formatter->asDate($this->created_at) . ' - ' .
            $this->getProductFullname() . ' - ' .
            $this->quantity;
    }

    /**
     * @return Request[]
     */
    public static function getActiveAll()
    {
        return Request::find()->where(['status' => Request::STATUS_ACTIVE])->all();
    }
}
