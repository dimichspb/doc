<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $country
 * @property integer $city
 * @property string $postcode
 * @property string $street
 * @property string $housenumber
 * @property string $building
 * @property string $office
 * @property string $comments
 *
 * @property City $city0
 * @property Country $country0
 * @property Entity[] $entities
 * @property Entity[] $entities0
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country', 'city'], 'integer'],
            [['postcode', 'street', 'housenumber'], 'required'],
            [['postcode'], 'string', 'max' => 6],
            [['street', 'comments'], 'string', 'max' => 255],
            [['housenumber', 'building', 'office'], 'string', 'max' => 10],
            [['city'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city' => 'id']],
            [['country'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => 'Country',
            'city' => 'City',
            'postcode' => 'Postcode',
            'street' => 'Street',
            'housenumber' => 'Housenumber',
            'building' => 'Building',
            'office' => 'Office',
            'comments' => 'Comments',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity0()
    {
        return $this->hasOne(City::className(), ['id' => 'city']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry0()
    {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntities()
    {
        return $this->hasMany(Entity::className(), ['address' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntities0()
    {
        return $this->hasMany(Entity::className(), ['factaddress' => 'id']);
    }
}
