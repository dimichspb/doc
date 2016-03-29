<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "person".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $middlename
 * @property integer $birthday
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname'], 'required'],
            [['birthday'], 'integer'],
            [['firstname', 'lastname', 'middlename'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'middlename' => 'Отчество',
            'birthday' => 'Дата рождения',
        ];
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->firstname . ' ' . (isset($this->middlename)?  $this->middlename . ' ': '') . $this->lastname;
    }
}
