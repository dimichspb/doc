<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RequestForm is the model behind the new request form.
 */
class RequestForm extends Model
{
    public $inn;
    public $email;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['inn', 'email'], 'required'],
            [['inn', 'email'], 'trim'],
            [['inn'], 'match', 'pattern' => '/^[0-9]{10,12}$/'],
            [['email'], 'email'],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'inn' => 'ИНН',
            'email' => 'Электронная почта',
        ];
    }
}
