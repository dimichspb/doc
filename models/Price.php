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
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;

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
            [['status', 'created_at', 'updated_at', 'product', 'supplier', 'quantity'], 'integer'],
            [['started_at', 'expire_at'], 'date'],
            [['started_at', 'product', 'supplier', 'value'], 'required'],
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

    public function afterFind()
    {
        $today = new \DateTime();
        if ($this->status === self::STATUS_ACTIVE && isset($this->expire_at) && $this->expire_at <= $today->getTimestamp()) {
            $this->status = self::STATUS_INACTIVE;
            $this->save();
        }
        parent::afterFind();
    }

    /**
     * @return string
     */
    public function getProductCode()
    {
        return $this->getProductOne()->code;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->getProductOne()->name;
    }

    public function getProductFullname()
    {
        return $this->getProductOne()->fullname;
    }

    /**
     * @return string
     */
    public function getSupplierName()
    {
        return $this->getSupplierOne()->name;
    }

    public function getStatusName()
    {
        $statusArray = Price::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
    }

    public function beforeSave($insert)
    {
        $today = new \DateTime();

        $this->updated_at = $today->getTimestamp();

        if ($insert) $this->created_at = $today->getTimestamp();

        if (!empty($this->started_at)) {
            $this->started_at = Yii::$app->formatter->asTimestamp($this->started_at);
        }
        if (!empty($this->expire_at)) {
            $this->expire_at = Yii::$app->formatter->asTimestamp($this->expire_at);
        }

        if (!empty($this->value)) {
            $this->value = str_replace(',', '.', $this->value);
        }

        if (empty($this->quantity)) {
            $this->quantity = 0;
        }

        return parent::beforeSave($insert);
    }

    public static function getStatusArray()
    {
        $statusArray = [
            Price::STATUS_ACTIVE => 'Текущая',
            Price::STATUS_INACTIVE => 'Истекла',
            Price::STATUS_DELETED => 'Удалена',
        ];
        return $statusArray;
    }
}
