<?php

namespace app\models;

use Yii;
use app\models\Request;
use app\models\Quotation;
use app\models\QuotationToProduct;

/**
 * This is the model class for table "request_to_product".
 *
 * @property integer $quantity
 *
 * @property integer $product
 * @property integer $request
 */
class RequestToProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request_to_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request', 'product', 'quantity'], 'integer'],
            [['product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product' => 'id']],
            [['request'], 'exist', 'skipOnError' => true, 'targetClass' => Request::className(), 'targetAttribute' => ['request' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request' => 'Запрос',
            'product' => 'Товар',
            'quantity' => 'Количество',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(Request::className(), ['id' => 'request']);
    }

    /**
     * @return Product
     */
    public function getProductOne()
    {
        return $this->getProduct()->one();
    }

    /**
     * @return Request
     */
    public function getRequestOne()
    {
        return $this->getRequest()->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotations()
    {
        return $this->getRequestOne()->getQuotations();
    }

    /**
     * @return Quotation[]
     */
    public function getQuotationsAll()
    {
        return $this->getQuotations()->all();
    }

    public function getQuotationProducts()
    {
        return 
    }

    public function getPrice()
    {
        $quotationToProducts = $this
    }
}
