<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 30;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;

    public $password;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['email'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['password_hash', 'password_reset_token', 'email', 'access_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username', 'password'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['role'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_key' => 'Auth Key',
            'password' => 'Пароль',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'username' => 'Логин',
            'email' => 'Электронная почта',
            'role' => 'Роль',
            'status' => 'Статус',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'access_token' => 'Access Token',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * Finds user by email
     *
     * @param string $email
     * @return User
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }
    
    public static function searchByEmail($email)
    {
        $user = User::findByEmail($email);
        if (!$user) {
            $user = new User();
            $user->email = $email;
        }
        return $user;
    }

    /**
     * Finds user by username
     *
     * @param string $authKey
     * @return static|null
     */
    public static function findByAuthKey($authKey)
    {
        return static::findOne(['auth_key' => $authKey, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates access token  and sets it to the model
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Do some changes before save user into DB
     *
     * @param $insert boolean
     * @return boolean
     */
    public function beforeSave($insert)
    {
        $today = new \DateTime();
        $this->updated_at = $today->getTimestamp();
        if ($insert) {
            $this->created_at = $today->getTimestamp();
            $this->generateAuthKey();
            $this->generateAccessToken();
        }
       
        if ($this->password) {
            $this->setPassword($this->password);
            $message = 'Ваш пароль быз изменен';
            Yii::$app->session->setFlash('info', $message);
        }       
       
        if (!Yii::$app->user->isGuest && $this->id == Yii::$app->user->getIdentity()->getId()) {
            if ($this->status != $this->getOldAttribute('status')) {
                $this->status = $this->getOldAttribute('status');
                $message = 'Вы не можете изменить свой статус';
                Yii::$app->session->setFlash('warning', $message);
            }
        }
        
        if (!$this->username) {
            $this->username = $this->email;
            $this->setPassword($this->email);
        }
        
        return parent::beforeSave($insert);
    }

    /**
     * @param $userId
     * @return User
     */
    public static function getUserById($userId)
    {
        return User::findOne([
            'id' => $userId
        ]);
    }

    public static function getAllRoles()
    {
        $allRoles = Yii::$app->authManager->getRoles();
        return ArrayHelper::map($allRoles, 'name', 'name');
    }

    public static function getUserRoles($id)
    {
        $userRoles = Yii::$app->authManager->getRolesByUser($id);
        return $userRoles;
    }

    public function getRoles()
    {
        return User::getUserRoles($this->id);
    }

    public function getFirstRole()
    {
        $userRoles = $this->getRoles();
        return array_shift($userRoles);
    }

    public static function getStatusArray()
    {
        return [
            User::STATUS_DELETED => 'Удален',
            User::STATUS_INACTIVE => 'Неактивен',
            User::STATUS_ACTIVE => 'Активен',
        ];
    }

    public function getStatus()
    {
        $statusArray = User::getStatusArray();
        return isset($statusArray[$this->status])? $statusArray[$this->status]: '';
    }

    public function getRole()
    {
        return array_keys($this->getRoles());
    }

    public function setRole($rolesArray)
    {
        if (is_array($rolesArray)) {
            Yii::$app->authManager->revokeAll($this->id);
            foreach ($rolesArray as $item) {
                if (!ArrayHelper::keyExists($item, $this->getRoles()) && $this->id) {
                    Yii::$app->authManager->assign(Yii::$app->authManager->getRole($item), $this->id);
                }
            }
        }
    }
    
    /**
    * @return ActiveQuery
    */
    public function getUserToCustomers()
    {
        return $this->hasMany(UserToCustomer::className(), ['user' => 'id']);
    }
    
    /**
    * @return ActiveQuery
    */
    public function getUserToSuppliers()
    {
        return $this->hasMany(UserToSupplier::className(), ['user' => 'id']);
    }
    
    /**
    * @return ActiveQuery
    */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['id' => 'customer'])->via('userToCustomers');
    }
    
    /**
    * @return ActiveQuery
    */
    public function getSuppliers()
    {
        return $this->hasMany(Supplier::className(), ['id' => 'supplier'])->via('userToSuppliers');
    }
    
    /**
    * @return Customer[]
    */
    public function getCustomersAll()
    {
        return $this->getCustomers()->exists()? $this->getCustomers()->all(): null;
    }
    
    public function getCustomerFirst()
    {
        return $this->getCustomers()->exists()? $this->getCustomers()->first(): null;
    }
    
    /**
    * @return Supplier[]
    */
    public function getSuppliersAll()
    {
        return $this->getSuppliers()->exists()? $this->getSuppliers()->all(): null;
    }
    
    
    
    public static function findFirst()
    {
        return User::find()->one();
    }
    
    public function getEntities()
    {
        return $this->hasMany(Entity::className(), ['id' => 'entity'])->via('customerToEntities');
    }
    
    public function getCustomerToEntities()
    {
        return $this->hasMany(CustomerToEntity::className(), ['customer' => 'customer'])->via('userToCustomers');
    }
    
    public function getEntityFirst()
    {
        return $this->getEntities()->exists()? $this->getEntities()->one(): null;
    }
}
