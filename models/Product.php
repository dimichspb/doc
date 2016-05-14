<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;


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
class Product extends ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;
    
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;

    const IMAGES_DIR = 'product/images';
    const DRAWINGS_DIR = 'product/drawings';
    
    const DEFAULT_COUNT = '100';

    public $imageFile;
    public $drawingFile;

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
            [['imageFile'], 'image', 'skipOnEmpty' => true,],
            [['drawingFile'], 'image', 'skipOnEmpty' => true,],
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
            'length' => 'Длина',
            'package' => 'Упаковка, шт',
            'stock' => 'Остаток, шт',
            'price' => 'Цена, руб/шт',
            'imageFile' => 'Изображение',
            'image_file' => 'Изображение',
            'drawingFile' => 'Чертеж',
            'drawing_file' => 'Чертеж',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['product' => 'id']);
    }

    /**
     * @return Price[]
     */
    public function getPricesAll()
    {
        return $this->getPrices()->all();
    }

    /**
     * @return Price|null
     */
    public function getValidPrice()
    {
        $today = new \DateTime();
        $query = $this->getPrices()
            ->where(['status' => Price::STATUS_ACTIVE])
            ->andWhere('`started_at` <= \'' . $today->getTimestamp() . '\' AND (`expire_at` IS NULL OR `expire_at` > \'' . $today->getTimestamp() . '\')')
            ->orderBy(['started_at' => SORT_ASC]);
        return $query->one();
    }

    /**
     * @return float
     */
    public function getValidPriceValue()
    {
        $validPrice = $this->getValidPrice();
        return $validPrice? $validPrice->value: 0.00;
    }

    public function getFullname()
    {
        return $this->code . ' ' . $this->name . ' ' . $this->dia;
    }

    /**
     * @return Product[]
     */
    public static function getActiveAll()
    {
        return Product::find()->where(['status' => Product::STATUS_ACTIVE])->all();
    }

    public function getStatusName()
    {
        $statusArray = Product::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
    }

    public static function getStatusArray()
    {
        $statusArray = [
            Product::STATUS_ACTIVE => 'Активен',
            Product::STATUS_INACTIVE => 'Неактивен',
            Product::STATUS_DELETED => 'Удален',
        ];
        return $statusArray;
    }
    
    public function upload()
    {
        if ($this->validate()) {
            if (isset($this->imageFile)) {
                FileHelper::createDirectory(Product::IMAGES_DIR);
                $fileName = $this->imageFile->baseName . '.' . $this->imageFile->extension;
                if ($this->imageFile->saveAs(Product::IMAGES_DIR . DIRECTORY_SEPARATOR . $fileName)) {
                    $this->image_file = $fileName;
                    $this->update(false, ['image_file']);
                } else {
                    $message = "Ошибка загрузки файла $fileName";
                }
            }
            if (isset($this->drawingFile)) {
                FileHelper::createDirectory(Product::DRAWINGS_DIR);
                $fileName = $this->drawingFile->baseName . '.' . $this->drawingFile->extension;
                if ($this->drawingFile->saveAs(Product::DRAWINGS_DIR . DIRECTORY_SEPARATOR . $fileName)) {
                    $this->drawing_file = $fileName;
                    $this->update(false, ['drawing_file']);
                } else {
                    $message = "Ошибка загруки файла $fileName";
                }
            }
            if (isset($message)) {
                Yii::$app->session->setFlash('danger', $message);
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function getImageFilePath()
    {
        $filePath = DIRECTORY_SEPARATOR . Product::IMAGES_DIR . DIRECTORY_SEPARATOR . $this->image_file;
        return ($this->image_file && is_readable(Yii::getAlias('@webroot') . $filePath))?
            $filePath:
            DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . '1x1.png';
    }
    
    public function getDrawingFilePath()
    {
        $filePath = DIRECTORY_SEPARATOR . Product::DRAWINGS_DIR . DIRECTORY_SEPARATOR . $this->drawing_file;
        return ($this->drawing_file && is_readable(Yii::getAlias('@webroot') . $filePath))?
            $filePath:
            DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . '1x1.png';
    }
    
    /**
    * @param integer
    * @return app\models\Product
    */
    public static function getProductById($productId)
    {
        $query =  Product::find()->where(['id' => $productId]);
        return $query->one();    
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getPrice()
    {
        return $this->getValidPriceValue();
    }
    
    public function getCount()
    {
        return Product::DEFAULT_COUNT;
    }

    public function getFullCode()
    {
        return ($this->code) .
            (isset($this->dia)? ' Ø' . $this->dia:'') .
            (isset($this->thread)? ' M' . $this->thread: '');
    }

    public function getDia()
    {
        return (isset($this->dia))? 'Ø' . $this->dia: 'не задан';
    }

    public function getThread()
    {
        return (isset($this->thread))? 'M' . $this->thread: 'не задана';
    }

    public function getLength()
    {
        return (isset($this->length))? $this->length . ' мм': 'не задана';
    }

    public function getPackage()
    {
        return (isset($this->package))? $this->package: 'не указана';
    }
}
