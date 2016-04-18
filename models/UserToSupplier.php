<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_to_supplier".
 *
 * @property integer $user
 * @property integer $supplier
 *
 * @property Supplier $supplier0
 * @property User $user0
 */
class UserToSupplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_to_supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'supplier'], 'required'],
            [['user', 'supplier'], 'integer'],
            [['supplier'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplier' => 'id']],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user' => 'User',
            'supplier' => 'Supplier',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier0()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
}
