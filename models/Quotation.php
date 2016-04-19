<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "quotation".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $expire_at
 *
 * @property Request $request
 * @property Supplier $supplier
 */
class Quotation extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;
    const STATUS_ORDERED = 11;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quotation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'request', 'supplier'], 'integer'],
            [['expire_at'], 'date', 'timestampAttribute' => 'expire_at'],
            [['expire_at'], 'default', 'value' => null],
            [['request', 'supplier'], 'required'],
            [['request'], 'exist', 'skipOnError' => true, 'targetClass' => Request::className(), 'targetAttribute' => ['request' => 'id']],
            [['supplier'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplier' => 'id']],
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
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
            'expire_at' => 'Действует до',
            'request' => 'Запрос',
            'supplier' => 'Поставщик',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(Request::className(), ['id' => 'request']);
    }

    /**
     * @return Request
     */
    public function getRequestOne()
    {
        return $this->getRequest()->one();
    }

    /**
     * @return string
     */
    public function getRequestName()
    {
        return $this->getRequestOne()->getName();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier']);
    }

    /**
     * @return Supplier
     */
    public function getSupplierOne()
    {
        return $this->getSupplier()->one();
    }

    public function getSupplierName()
    {
        return $this->getSupplierOne()->name;
    }

    public function getStatusName()
    {
        $statusArray = Quotation::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
    }

    public static function getStatusArray()
    {
        $statusArray = [
            Quotation::STATUS_ACTIVE => 'Новое',
            Quotation::STATUS_ORDERED => 'Заказано',
            Quotation::STATUS_INACTIVE => 'Неактивно',
            Quotation::STATUS_DELETED => 'Удалено',
        ];
        return $statusArray;
    }

    public function getName()
    {
        return implode(' - ', [
            $this->id,
            //Yii::$app->formatter->asDate($this->created_at),
            $this->getRequestName(),
            $this->getSupplierName(),  
        ]);
    }

    /**
     * @return ActiveQuery
     */
    public static function getActive()
    {
        return Quotation::find()->where(['status' => Quotation::STATUS_ACTIVE]);
    }
    
    /**
     * @return Quotation[]
     */
    public static function getActiveAll()
    {
        return Quotation::getActive()->all();
    }
    
    /**
     * @return ActiveQuery
     */
    public static function getOrdered()
    {
        return Quotation::find()->where(['status' => Quotation::STATUS_ORDERED]);
    }

    /**
     * @return Quotation[]
     */
    public static function getOrderedAll()
    {
        return Quotation::getOrdered()->all();
    }

    /**
     * @return Quotation|void
     */
    public static function getFirst()
    {
        $firstActiveQuotation = Quotation::getActive()->one();
        if ($firstActiveQuotation) {
            return $firstActiveQuotation;
        }
        $firstOrderedQuotation = Quotation::getOrdered()->one();
        if ($firstOrderedQuotation) {
            return $firstOrderedQuotation;
        }
    }
    
    /**
    * @return Quotation[]
    */
    public static function getActiveAndOrderedAll()
    {
        return Quotation::find()->where(['status' => Quotation::STATUS_ACTIVE])->orWhere(['status' => Quotation::STATUS_ORDERED])->all();
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

    /**
     * @return ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product'])->viaTable('{{%quotation_to_product}}', ['quotation' => 'id']);
    }

    /**
     * @return Product[]
     */
    public function getProductsAll()
    {
        return $this->getProducts()->all();
    }

    /**
     *
     * @return ActiveQuery
     */
    public function getQuotationToProducts()
    {
        return $this->hasMany(QuotationToProduct::className(), ['quotation' => 'id']);
    }

    /**
     * @param integer|null $id
     * @return QuotationToProduct[]
     */
    public function getQuotationToProductsAll($id = null)
    {
        if (is_null($id)) {
            return $this->getQuotationToProducts()->all();
        }
        $requestToProducts = $this->getRequestToProductsAll($id);
        $quotationToProducts = [];
        foreach ($requestToProducts as $requestToProduct) {
            $quotationToProduct = new QuotationToProduct();
            $quotationToProduct->product = $requestToProduct->product;
            $quotationToProduct->quantity = $requestToProduct->quantity;
            $quotationToProducts[] = $quotationToProduct;
        }
        return $quotationToProducts;
    }

    /**
     * @param integer|null $id
     * @return ActiveQuery
     */
    public function getRequestToProducts($id = null)
    {
        if (!is_null($id)) {
            $request = Request::findOne(['id' => $id]);
            return $request->getRequestToProducts();
        }

        if (isset($this->request)) {
            return $this->getRequestOne()->getRequestToProducts();
        }
    }

    /**
     * @param integer|null $id
     * @return RequestToProduct[]
     */
    public function getRequestToProductsAll($id = null)
    {
        return $this->getRequestToProducts($id)->all();
    }
    
    /**
    * @return ActiveQuery
    */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['quotation' => 'id']);
    }
    
    /**
    * @return ActiveQuery
    */
    public function getActiveOrders()
    {
        return $this->getOrders()->where(['status' => Order::STATUS_ACTIVE]);
    }
    
    public function afterFind()
    {
        if ($this->getActiveOrders()->exists() && $this->status === Quotation::STATUS_ACTIVE) {
            $this->status = Quotation::STATUS_ORDERED;
            $this->update(false, ['status']);
        }
        parent::afterFind(); // TODO: Change the autogenerated stub
    }
}
