<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "price".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $started_at
 * @property integer $expire_at
 * @property integer $quantity
 * @property double $value
 *
 * @property Product $product
 * @property Supplier $supplier
 */
class Price extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'started_at', 'expire_at', 'product', 'supplier', 'quantity'], 'integer'],
            [['created_at', 'updated_at', 'started_at', 'product', 'supplier', 'value'], 'required'],
            [['value'], 'number'],
            [['product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product' => 'id']],
            [['supplier'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplier' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'created_at' => 'Добавлена',
            'updated_at' => 'Изменена',
            'started_at' => 'Актуальна с',
            'expire_at' => 'Актуальна до',
            'product' => 'Товар',
            'supplier' => 'Поставщик',
            'quantity' => 'Количество',
            'value' => 'Цена',
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
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier']);
    }

    /**
     * @return Product
     */
    public function getProductOne()
    {
        return $this->getProduct()->one();
    }

    /**
     * @return Supplier
     */
    public function getSupplierOne()
    {
        return $this->getSupplier()->one();
    }
}
