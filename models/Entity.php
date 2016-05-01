<?php

namespace app\models;

use app\models\Address;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "entity".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $created_by
 * @property integer $entity_form
 * @property string $name
 * @property string $fullname
 * @property string $ogrn
 * @property string $inn
 * @property string $kpp
 *
 * @property CustomerToEntity[] $customerToEntities
 * @property Account $account
 * @property EntityPersonRole $accountant
 * @property Address $address
 * @property User $createdBy
 * @property EntityPersonRole $director
 * @property EntityForm $entityForm
 * @property Address $factaddress
 * @property SupplierToEntity[] $supplierToEntities
 */
class Entity extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'entity_form', 'address', 'factaddress', 'account', 'director', 'accountant'], 'integer'],
            [['entity_form', 'inn'], 'required'],
            [['name', 'fullname'], 'string', 'max' => 255],
            [['ogrn'], 'string', 'max' => 15],
            [['inn'], 'string', 'max' => 12],
            [['kpp'], 'string', 'max' => 9],
            [['inn'], 'unique'],
            [['ogrn'], 'unique'],
            [['account'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account' => 'id']],
            [['accountant'], 'exist', 'skipOnError' => true, 'targetClass' => EntityPersonRole::className(), 'targetAttribute' => ['accountant' => 'id']],
            [['address'], 'exist', 'skipOnError' => true, 'targetClass' => Address::className(), 'targetAttribute' => ['address' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['director'], 'exist', 'skipOnError' => true, 'targetClass' => EntityPersonRole::className(), 'targetAttribute' => ['director' => 'id']],
            [['entity_form'], 'exist', 'skipOnError' => true, 'targetClass' => EntityForm::className(), 'targetAttribute' => ['entity_form' => 'id']],
            [['factaddress'], 'exist', 'skipOnError' => true, 'targetClass' => Address::className(), 'targetAttribute' => ['factaddress' => 'id']],
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
            'created_by' => 'Добавлено',
            'entity_form' => 'Правовая форма',
            'name' => 'Наименование',
            'full' => 'Наименование',
            'fullname' => 'Полное наименование',
            'ogrn' => 'ОГРН',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'address' => 'Юридический адрес',
            'addressFull' => 'Юридический адрес',
            'factaddress' => 'Фактический адрес',
            'account' => 'Счет',
            'director' => 'Директор',
            'accountant' => 'Бухгалтер',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerToEntities()
    {
        return $this->hasMany(CustomerToEntity::className(), ['entity' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account']);
    }

    /**
     * @return Account
     */
    public function getAccountOne()
    {
        return $this->getAccount()->one();
    }

    /**
     * @return string
     */
    public function getAccountFull()
    {
        return $this->getAccountOne()->getFull();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountant()
    {
        return $this->hasOne(EntityPersonRole::className(), ['id' => 'accountant']);
    }

    /**
     * @return EntityPersonRole
     */
    public function getAccountantOne()
    {
        return $this->getAccountant()->one();
    }

    /**
     * @return string
     */
    public function getAccountantFull()
    {
        return $this->getAccountantOne()->getFull();
    }

    /**
     * @return ActiveRecord
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['id' => 'address'])->viaTable('{{%address_to_entity}}', ['entity' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address']);
    }

    /**
     * @return Address
     */
    public function getAddressOne()
    {
        return $this->getAddress()->one();
    }

    public function getAddressFull()
    {
        return $this->getAddressOne()->getFull();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirector()
    {
        return $this->hasOne(EntityPersonRole::className(), ['id' => 'director']);
    }

    /**
     * @return EntityPersonRole
     */
    public function getDirectorOne()
    {
        return $this->getDirector()->one();
    }

    /**
     * @return string
     */
    public function getDirectorFull()
    {
        return $this->getDirectorOne()->getFull();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntityForm()
    {
        return $this->hasOne(EntityForm::className(), ['id' => 'entity_form']);
    }

    /**
     * @return EntityForm
     */
    public function getEntityFormOne()
    {
        return $this->getEntityForm()->one();
    }

    /**
     * @return string
     */
    public function getEntityFormName()
    {
        return $this->getEntityFormOne()->name;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'factaddress']);
    }

    /**
     * @return Address
     */
    public function getFactAddressOne()
    {
        return $this->getFactaddress()->one();
    }

    public function getFactAddressFull()
    {
        return $this->getFactAddressOne()->getFull();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierToEntities()
    {
        return $this->hasMany(SupplierToEntity::className(), ['entity' => 'id']);
    }

    public function getStatusName()
    {
        $statusArray = Entity::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
    }

    public static function getStatusArray()
    {
        $statusArray = [
            Entity::STATUS_ACTIVE => 'Активен',
            Entity::STATUS_INACTIVE => 'Неактивен',
            Entity::STATUS_DELETED => 'Удален',
        ];
        return $statusArray;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFull()
    {
        return $this->getEntityFormName() . ' ' . $this->getName();
    }
    
    public function getEntityPersonRoles()
    {
        return $this->hasMany(EntityPersonRole::className(), ['entity' => 'id']);
    }

    public function getEntityPersonRoleArray()
    {
        return ArrayHelper::map(EntityPersonRole::find()->where(['entity' => $this->id])->all(), 'id', 'full');
    }

    public function getAddressArray()
    {
        return ArrayHelper::map($this
                ->hasMany(Address::className(), ['id' => 'address'])
                ->viaTable('{{%address_to_entity}}', ['entity' => 'id'])
                ->all(), 'id', 'full');
    }

    public function getAccountArray()
    {
        return ArrayHelper::map(Account::find()->where(['entity' => $this->id])->all(), 'id', 'full');
    }

    public function getRoleArray()
    {
        return ArrayHelper::map(EntityRole::find()->all(), 'id', 'name');
    }

    public function getPersonArray()
    {
        return ArrayHelper::map($this->getPersons()->all(), 'id', 'fullname');
    }

    /**
     * @return ActiveQuery
     */
    public function getPersons()
    {
        return $this->hasMany(Person::className(), ['id' => 'person'])->viaTable('{{%entity_person_role}}', ['entity' => 'id']);
    }

    /**
     * @param $id
     * @return Entity
     */
    public static function findById($id)
    {
        return Entity::find()->where(['id' => $id])->one();
    }
    
    /**
     * @param $inn
     * @return Entity
     */
    public static function findByInn($inn)
    {
        return Entity::find()->where(['inn' => $inn])->one();
    }

    /**
     * @return ActiveQuery
     */
    public static function getActive()
    {
        return Entity::find()->where(['status' => Entity::STATUS_ACTIVE]);
    }

    /**
     * @return Entity[]
     */
    public static function getActiveAll()
    {
        return Entity::getActive()->all();
    }
    
    public function getEntityRoles()
    {
        return $this->hasMany(EntityRole::className(), ['entity' => 'id']);
    }
    
    public function beforeSave($insert)
    {
        if (empty($this->created_by)) {
            $user = User::findFirst();
            $this->created_by = $user->id;
        }
        return parent::beforeSave($insert);
    }
}
