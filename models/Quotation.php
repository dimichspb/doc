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
 * @property integer $request
 * @property integer $supplier
 * @property double $value
 *
 * @property Request $request0
 * @property Supplier $supplier0
 */
class Quotation extends \yii\db\ActiveRecord
{
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
            'id' => 'ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'expire_at' => 'Expire At',
            'request' => 'Request',
            'supplier' => 'Supplier',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest0()
    {
        return $this->hasOne(Request::className(), ['id' => 'request']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier0()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier']);
    }
}
