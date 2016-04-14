<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quotation_to_product".
 *
 * @property integer $quotation
 * @property integer $product
 * @property integer $quantity
 * @property double $price
 */
class QuotationToProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quotation_to_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quotation', 'product', 'quantity'], 'integer'],
            [['price'], 'number', 'min' => 0],
            [['product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product' => 'id']],
            [['quotation'], 'exist', 'skipOnError' => true, 'targetClass' => Quotation::className(), 'targetAttribute' => ['quotation' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'quotation' => 'Предложение',
            'product' => 'Товар',
            'quantity' => 'Количество',
            'price' => 'Цена',
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
     * @return Product
     */
    public function getProductOne()
    {
        return $this->getProduct()->one();
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
}
