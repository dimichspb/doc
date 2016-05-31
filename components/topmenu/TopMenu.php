<?php

namespace app\components\topmenu;

use app\models\MenuItemToRole;
use yii\helpers\ArrayHelper;
use Yii;
use yii\base\Component;
use app\models\MenuItem;
use yii\db\ActiveQuery;
use yii\rbac\Role;

class TopMenu extends Component
{
    /**
     * @param null $parent
     * @return ActiveQuery
     */
    public function getItems($parent = null)
    {
        return MenuItem::findItems($parent);
    }
    
    public function getItemsByRoles(Array $roles)
    {
        $menuItems = [];
        foreach ($this->getItems()->all() as $menuItem) {
            if ($menuItem->isAllowOneOf($roles)) {
                $menuItems[] = $menuItem;
            } 
        }
        return $menuItems;
    }
    
    public function asArray() 
    {
        $resultArray = [];
        $userRoles = !Yii::$app->user->isGuest? Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id): [];
        $menuItems = $this->getItemsByRoles($userRoles);
        foreach ($menuItems as $menuItem) {
            $menuItemArray = [
                'label' => $menuItem->label,
                'icon' => $menuItem->icon,
                'url' => $menuItem->action,
            ];
            if ($this->getItems($menuItem->id)->exists()) {
                $subItems = [];
                foreach($this->getItems($menuItem->id)->all() as $subItem) {
                    $subItems[] = [
                        'label' => $subItem->label,
                        'icon' => $subItem->icon,
                        'url' => $subItem->action,
                    ];
                }
                $menuItemArray['items'] = $subItems;
            }
            $resultArray[] = $menuItemArray;
        }
        return $resultArray;
    }

    public function addItem($icon, $label, $action = '#', $parent = null, $roles = [])
    {
        $menuItem = new MenuItem();
        $menuItem->icon = $icon;
        $menuItem->label = $label;
        $menuItem->action = $action;
        $menuItem->parent = $parent;
        if ($menuItem->save()) {
            foreach ($roles as $role) {
                $menuItemToRole = new MenuItemToRole();
                $menuItemToRole->menu_item = $menuItem->id;
                $menuItemToRole->role = $role;
                $menuItemToRole->save();
            }
            return $menuItem->id;
        }
        return false;
    }

    public function addItems(Array $items, $parentId = null)
    {
        foreach ($items as $item) {
            $itemId = $this->addItem($item['icon'], $item['label'], isset($item['action'])? $item['action']: '#', $parentId, isset($item['roles'])? $item['roles']: []);
            if (isset($item['items'])) {
                $this->addItems($item['items'], $itemId);
            }
        }
    }

    public function removeAll()
    {
        $menuItems = MenuItem::find()->where(['not', ['parent' => null]]);
        if ($menuItems->exists()) {
            foreach($menuItems->all() as $menuItem) {
                $menuItem->delete();
            }
        }
        $menuItems = MenuItem::find();
        if ($menuItems->exists()) {
            foreach($menuItems->all() as $menuItem) {
                $menuItem->delete();
            }
        }
    }
}