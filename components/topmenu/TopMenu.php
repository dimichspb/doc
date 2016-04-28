<?php

namespace app\components\topmenu;

use Yii;
use yii\base\Component;
use app\models\MenuItem;
use yii\rbac\Role;

class TopMenu extends Component
{
    public function getItems()
    {
        return MenuItem::findItems();
    }
    
    public function getItemsByRoles(array $roles)
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
            $resultArray[] = $menuItem->asArray();
        }
        return $resultArray;
    }    
}