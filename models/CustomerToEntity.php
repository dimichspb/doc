<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_to_entity".
 *
 * @property integer $customer
 * @property integer $entity
 *
 */
class CustomerToEntity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_to_entity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer', 'entity'], 'required'],
            [['customer', 'entity'], 'integer'],
            [['customer'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer' => 'id']],
            [['entity'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::className(), 'targetAttribute' => ['entity' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer' => 'Customer',
            'entity' => 'Entity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Entity::className(), ['id' => 'entity']);
    }
}
