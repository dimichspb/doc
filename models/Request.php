<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "request".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property Quotation[] $quotations
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
            [['status', 'updated_at', 'customer'], 'integer'],
            [['created_at'], 'integer'],
            [['customer'], 'required'],
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
            Yii::$app->formatter->asDate($this->created_at);
    }

    /**
     * @return Request[]
     */
    public static function getActiveAll()
    {
        return Request::find()->where(['status' => Request::STATUS_ACTIVE])->all();
    }

    /**
     * @return ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product'])->viaTable('{{%request_to_product}}', ['request' => 'id']);
    }

    /**
     * @return Product[]
     */
    public function getProductsAll()
    {
        return $this->getProducts()->all();
    }

    /**
     * @return ActiveQuery
     */
    public function getRequestToProducts()
    {
        return $this->hasMany(RequestToProduct::className(), ['request' => 'id']);
    }
}
