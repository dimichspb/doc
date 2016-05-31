<?php

namespace app\models;

use Yii;
use Yii\rbac\Role;
use app\models\AuthItem;

/**
 * This is the model class for table "menu_item".
 *
 * @property integer $id
 * @property integer $parent
 * @property string $icon
 * @property string $label
 * @property string $action
 *
 * @property MenuItem $parent0
 * @property MenuItem[] $menuItems
 * @property MenuItemToRole[] $menuItemToRoles
 */
class MenuItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent'], 'integer'],
            [['icon', 'label', 'action'], 'string', 'max' => 255],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => MenuItem::className(), 'targetAttribute' => ['parent' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent' => 'Parent',
            'icon' => 'Icon',
            'label' => 'Label',
            'action' => 'Action',
        ];
    }
    
    public static function findItems($parent)
    {
        return MenuItem::find()->where(['parent' => $parent]);
        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'parent']);
    }
    
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'role'])->via('menuItemToRoles');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['parent' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemToRoles()
    {
        return $this->hasMany(MenuItemToRole::className(), ['menu_item' => 'id']);
    }
    
    public function getRoles()
    {
        $roles = [];
        $authItems = $this->getAuthItems()->all();
        foreach ($authItems as $authItem) {
            $roles[] = Yii::$app->authManager->getRole($authItem->name);
        }
        return $roles;
    }
    
    public function isAllow(Role $role)
    {
        return $this->getAuthItems()->where(['name' => $role->name])->exists();
    }
    
    public function isAllowOneOf(array $roles)
    {
        $result = false;
        foreach ($roles as $role) {
            $result = $this->isAllow($role)? true: $result;
        }
        return $result;
    }
    
    public function asArray()
    {
        switch ($this->getMenuItems()->exists()) {
            case true:
                $result = $this->asParentItem();
                break;
            case false:
                $result = $this->asChildItem();
                break;
            default:
                $result = [];
        }
        return $result;
    }
    
    public function asParentItem()
    {
        return [
            'label' => $this->getLabel(),
            'items' => $this->getChildren(),
        ];    
    }
    
    public function asChildItem()
    {
        return [
            'label' => $this->getLabel(),
            'url' => [$this->action],
        ];
    }
    
    public function getChildren()
    {
        $children = [];
        foreach ($this->getMenuItems()->all() as $child) {
            $children[] = $child->asChildItem();
        }
        return $children;
    }
    
    public function getLabel()
    {
        return (isset($this->icon)? '<span class="' . $this->icon. '"></span> ': ' ') . $this->label;
    }
}
