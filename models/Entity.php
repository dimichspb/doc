<?php

namespace app\models;

use Yii;

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
 * @property integer $address
 * @property integer $factaddress
 * @property integer $account
 * @property integer $director
 * @property integer $accountant
 *
 * @property CustomerToEntity[] $customerToEntities
 * @property Account $account0
 * @property EntityPersonRole $accountant0
 * @property Address $address0
 * @property User $createdBy
 * @property EntityPersonRole $director0
 * @property EntityForm $entityForm
 * @property Address $factaddress0
 * @property SupplierToEntity[] $supplierToEntities
 */
class Entity extends \yii\db\ActiveRecord
{
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
            [['created_by', 'entity_form', 'inn'], 'required'],
            [['name', 'fullname'], 'string', 'max' => 255],
            [['ogrn'], 'string', 'max' => 13],
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
            'status' => 'Status',
            'created_by' => 'Created By',
            'entity_form' => 'Entity Form',
            'name' => 'Name',
            'fullname' => 'Fullname',
            'ogrn' => 'Ogrn',
            'inn' => 'Inn',
            'kpp' => 'Kpp',
            'address' => 'Address',
            'factaddress' => 'Factaddress',
            'account' => 'Account',
            'director' => 'Director',
            'accountant' => 'Accountant',
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
    public function getAccount0()
    {
        return $this->hasOne(Account::className(), ['id' => 'account']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountant0()
    {
        return $this->hasOne(EntityPersonRole::className(), ['id' => 'accountant']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress0()
    {
        return $this->hasOne(Address::className(), ['id' => 'address']);
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
    public function getDirector0()
    {
        return $this->hasOne(EntityPersonRole::className(), ['id' => 'director']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntityForm()
    {
        return $this->hasOne(EntityForm::className(), ['id' => 'entity_form']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactaddress0()
    {
        return $this->hasOne(Address::className(), ['id' => 'factaddress']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierToEntities()
    {
        return $this->hasMany(SupplierToEntity::className(), ['entity' => 'id']);
    }
}
