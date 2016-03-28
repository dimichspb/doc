<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quotation".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $expire_at
 * @property double $value
 *
 * @property Request $request
 * @property Supplier $supplier
 */
class Quotation extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;

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
            [['status', 'created_at', 'updated_at', 'expire_at', 'request', 'supplier'], 'integer'],
            [['created_at', 'updated_at', 'request', 'supplier', 'value'], 'required'],
            [['value'], 'number'],
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
            'value' => 'Цена',
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
            Quotation::STATUS_ACTIVE => 'Активен',
            Quotation::STATUS_INACTIVE => 'Неактивен',
            Quotation::STATUS_DELETED => 'Удален',
        ];
        return $statusArray;
    }

    public function getName()
    {
        return
            $this->id . ' - ' .
            Yii::$app->formatter->asDate($this->created_at) . ' - ' .
            $this->getRequestName() . ' - ' .
            Yii::$app->formatter->asCurrency($this->value);

    }

    /**
     * @return Quotation[]
     */
    public static function getActiveAll()
    {
        return Quotation::find()->where(['status' => Quotation::STATUS_ACTIVE])->all();
    }
}
