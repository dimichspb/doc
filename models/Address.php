<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $postcode
 * @property string $street
 * @property string $housenumber
 * @property string $building
 * @property string $office
 * @property string $comments
 *
 * @property City $city
 * @property Country $country
 * @property Entity[] $entities
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
            'country' => 'Страна',
            'city' => 'Город',
            'postcode' => 'Индекс',
            'street' => 'Улица',
            'housenumber' => 'Номер дома',
            'building' => 'Строение',
            'office' => 'Офис',
            'comments' => 'Комментарии',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city']);
    }

    /**
     * @return City
     */
    public function getCityOne()
    {
        return $this->getCity()->exists()? $this->getCity()->one(): null;
    }

    public function getCityName()
    {
        return $this->getCityOne()? $this->getCityOne()->getName(): '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    /**
     * @return Country
     */
    public function getCountryOne()
    {
        return $this->getCountry()->exists()? $this->getCountry()->one(): null;
    }

    public function getCountryName()
    {
        return $this->getCountryOne()? $this->getCountryOne()->getName(): '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntitiesByAddress()
    {
        return $this->hasMany(Entity::className(), ['address' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntitiesByFactAddress()
    {
        return $this->hasMany(Entity::className(), ['factaddress' => 'id']);
    }

    /**
     * @return ActiveRecord
     */
    public function getEntities()
    {
        return $this->hasMany(Entity::className(), ['id' => 'entity'])->viaTable('{{%address_to_entity}}', ['address' => 'id']);
    }

    public function getStreetAddress()
    {
        return
            $this->street . ', ' .
            'д. ' . $this->housenumber .
            (!empty($this->building)? ', стр. ' . $this->building: '') .
            (!empty($this->office)? ', оф. '. $this->office: '');
    }

    /**
     * @return string
     */
    public function getFull()
    {
        return
            $this->getCountryName() . ', ' .
            $this->getCityName() . ', ' .
            $this->postcode . ', ' .
            $this->getStreetAddress();
    }

    /**
     * @return array
     */
    public static function getAddressArray()
    {
        return ArrayHelper::map(Address::find()->all(), 'id', 'full');
    }
}
