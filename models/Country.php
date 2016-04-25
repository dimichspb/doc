<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Address[] $addresses
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
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
            'id' => 'ID',
            'name' => 'Наименование',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['country' => 'id']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public static function getCountryArray()
    {
        return ArrayHelper::map(Country::find()->all(), 'id', 'name');
    }
    
    public static function findByFullName($fullName)
    {
        return Country::find()->where(['name' => $fullName])->one();
    }
    
    public static function findFirst()
    {
        $country = Country::find()->one();
        if (!$country) {
            $country = new Country();
            $country->name = 'Россия';
            $country->save();
        }
        return $country;
    }
}
