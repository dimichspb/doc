<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "supplier_to_entity".
 *
 * @property integer $supplier
 * @property integer $entity
 *
 * @property Entity $entity0
 * @property Supplier $supplier0
 */
class SupplierToEntity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier_to_entity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier', 'entity'], 'required'],
            [['supplier', 'entity'], 'integer'],
            [['entity'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::className(), 'targetAttribute' => ['entity' => 'id']],
            [['supplier'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplier' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'supplier' => 'Supplier',
            'entity' => 'Entity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity0()
    {
        return $this->hasOne(Entity::className(), ['id' => 'entity']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier0()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier']);
    }
}
