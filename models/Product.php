<?php

namespace app\models;

use Faker\Provider\DateTime;
use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $status
 * @property string $code
 * @property string $name
 * @property integer $dia
 * @property integer $thread
 * @property integer $package
 * @property integer $stock
 * @property float $price
 *
 * @property Material $material
 */
class Product extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'material', 'dia', 'thread', 'package'], 'integer'],
            [['code', 'name', 'material'], 'required'],
            [['code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 255],
            [['material'], 'exist', 'skipOnError' => true, 'targetClass' => Material::className(), 'targetAttribute' => ['material' => 'id']],
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
            'code' => 'Артикул',
            'name' => 'Наименование',
            'material' => 'Материал',
            'dia' => 'Диаметр',
            'thread' => 'Резьба',
            'package' => 'Упаковка, шт',
            'stock' => 'Остаток, шт',
            'price' => 'Цена, руб/шт',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Material::className(), ['id' => 'material']);
    }

    /**
     * @return Material
     */
    public function getMaterialOne()
    {
        return $this->getMaterial()->one();
    }

    /**
     * @return string
     */
    public function getMaterialName()
    {
        return $this->getMaterialOne()->name;
    }

    public function getStock()
    {
        return 0;
    }

    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['product' => 'id']);
    }

    public function getValidPrice()
    {
        $today = new \DateTime();
        $query = $this->getPrices()
            ->where(['started_at <= ' . $today->getTimestamp()])
            ->andWhere(['expire_at IS NULL OR expire_at > ' . $today->getTimestamp()]);
        var_dump($query->createCommand()->rawSql);
        die();
    }

    public function getValidPriceValue()
    {
        $this->getValidPrice();
        return 10.50;
    }
}
