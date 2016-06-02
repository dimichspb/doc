<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\data\ArrayDataProvider;

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
    const STATUS_QUOTED = 11;

    const EMAIL_LAYOUT_ADMIN = 'admin';
    const EMAIL_LAYOUT_SUPPLIER = 'supplier';
    const EMAIL_LAYOUT_CUSTOMER = 'customer';

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
            [['status', 'updated_at', 'customer', 'entity'], 'integer'],
            [['created_at'], 'integer'],
            [['customer', 'entity'], 'required'],
            [['entity'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::className(), 'targetAttribute' => ['entity' => 'id']],
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
            'entity' => 'Организация'
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
     * @return ActiveQuery
     */
    public function getActiveQuotations()
    {
        return $this->getQuotations()->where(['status' => Quotation::STATUS_ACTIVE]);
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

    /**
     * @return ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Entity::className(), ['id' => 'entity']);
    }

    /**
     * @return Entity
     */
    public function getEntityOne()
    {
        return $this->getEntity()->one();
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->getEntityOne()->getFull();
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        $statusArray = Request::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
    }

    public static function getStatusArray()
    {
        $statusArray = [
            Request::STATUS_ACTIVE => 'Новый',
            Request::STATUS_QUOTED => 'Обработан',
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
        return implode(' - ', [
            $this->id,
            //Yii::$app->formatter->asDate($this->created_at),
            $this->getCustomerName(),    
        ]);
    }

    /**
     * @return ActiveQuery
     */
    public static function getActive()
    {
        return Request::find()->where(['status' => Request::STATUS_ACTIVE]);
    }

    /**
     * @return Request[]
     */
    public static function getActiveAll()
    {
        return Request::getActive()->all();
    }

    /**
     * @return ActiveQuery
     */
    public static function getQuoted()
    {
        return Request::find()->where(['status' => Request::STATUS_QUOTED]);
    }

    /**
     * @return Request[]
     */
    public static function getQuotedAll()
    {
        return Request::getQuoted()->all();
    }

    /**
     * @return Request|void
     */
    public static function getFirst()
    {
        $firstActiveRequest = Request::getActive()->one();
        if ($firstActiveRequest) {
            return $firstActiveRequest;
        }
        $firstQuotedRequest = Request::getQuoted()->one();
        if ($firstQuotedRequest) {
            return $firstQuotedRequest;
        }
    }

    /**
     * @return ActiveQuery
     */
    public static function getActiveAndQuoted()
    {
        return Request::getActive()->orWhere(['status' => Request::STATUS_QUOTED]);
    }

    /**
     * @return Request[]
     */
    public static function getActiveAndQuotedAll()
    {
        return Request::getActiveAndQuoted()->all();
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

    /**
     * @return RequestToProduct[]
     */
    public function getRequestToProductsAll()
    {
        return $this->getRequestToProducts()->all();
    }

    public function afterFind()
    {
        if ($this->getActiveQuotations()->exists() && $this->status === Request::STATUS_ACTIVE) {
            $this->status = Request::STATUS_QUOTED;
            $this->update(false, ['status']);
        }
        parent::afterFind(); // TODO: Change the autogenerated stub
    }
    
    public function send()
    {
        $this->sendToAdmins();
        $this->sendToSuppliers();
        $this->sendToCustomer();
    }
    
    private function sendToAdmins()
    {
        $adminRole = Yii::$app->authManager->getRole('Admin');
        $admins = User::findByRole($adminRole);
        
        foreach($admins as $user) {
            $this->sendEmailTo($user, Request::EMAIL_LAYOUT_ADMIN);
        }
    }
    
    private function sendToSuppliers()
    {
        $suppliers = Supplier::getActiveAll();
        
        foreach($suppliers as $supplier) {
            foreach ($supplier->getUsersAll() as $user) {
                $this->sendEmailTo($user, Request::EMAIL_LAYOUT_SUPPLIER);
            }    
        }
    }
    
    private function sendToCustomer()
    {
        $customerUsers = $this->getCustomerOne()->getUsersAll();
        
        foreach($customerUsers as $user) {
            $this->sendEmailTo($user, Request::EMAIL_LAYOUT_CUSTOMER);
        }
    }
    
    private function sendEmailTo(User $user, $layout)
    {
        $products = $this->getRequestToProductsAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $products,
            'sort' => false,
        ]);
        
        Yii::$app->mailer->compose('request/' . $layout, ['request' => $this, 'dataProvider' => $dataProvider])
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
            ->setTo([$user->email => $user->username])
            ->setSubject('Запрос №'. $this->id .' с сайта')
            ->send(); 
    }

    public function productsHavePrices()
    {
        return count($this->getProductsWithPricesAll()) > 0;
    }

    public function getProductsWithPricesAll()
    {
        $products = $this->getProductsAll();
        foreach($products as $index => $product) {
            if ($product->getValidPriceValue($this->created_at) == 0) {
                unset($products[$index]);
            }
        }
        return $products;
    }

    public function createOrders()
    {
        $quotations = $this->createQuotations();
        foreach($quotations as $quotation) {
            $order = new Order();
            $order->quotation = $quotation->id;
            $order->save();
            foreach ($quotation->getQuotationToProductsAll() as $quotationToProduct) {
                $orderToProduct = new OrderToProduct();
                $orderToProduct->order = $order->id;
                $orderToProduct->product = $quotationToProduct->product;
                $orderToProduct->quantity = $quotationToProduct->quantity;
                $orderToProduct->price = $quotationToProduct->price;
                $orderToProduct->save();
            }
        }
    }

    /**
     * @return Quotation[]
     */
    public function createQuotations()
    {
        if ($this->productsHavePrices()) {
            $quotations = [];
            foreach($this->getProductsWithPricesAll() as $product) {
                $supplier = $product->getValidPrice($this->created_at)->getSupplierOne();
                if (!isset($quotations[$supplier->id])) {
                    $quotations[$supplier->id] = new Quotation();
                    $quotations[$supplier->id]->request = $this->id;
                    $quotations[$supplier->id]->supplier = $supplier->id;
                    $quotations[$supplier->id]->save();
                }
                $quotationToProduct = new QuotationToProduct();
                $quotationToProduct->quotation = $quotations[$supplier->id]->id;
                $quotationToProduct->product = $product->id;
                $quotationToProduct->quantity = $this->getRequestToProducts()->where(['product' => $product->id])->one()->quantity;
                $quotationToProduct->price = $product->getValidPriceValue($this->created_at);
                $quotationToProduct->save();
            }
            return $quotations;
        }
    }
}
