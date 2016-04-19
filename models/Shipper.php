<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shipper".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Delivery[] $deliveries
 */
class Shipper extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shipper';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер',
            'name' => 'Наименование',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveries()
    {
        return $this->hasMany(Delivery::className(), ['shipper' => 'id']);
    }
    
    /**
    * @return Delivery[]
    */
    public function getDeliveriesAll()
    {
        return $this->getDeliveries()->all();
    }
    
    /**
    * @return ActiveQuery
    */
    public static function getActive()
    {
        return Shipper::find();
    }
    
    /**
    * @return Shipper[]
    */
    public static function getActiveAll()
    {
        return Shipper::getActive()->all();
    }
    
    public function getName()
    {
        return $this->name;
    }
}
