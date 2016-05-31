<?php

use yii\db\Migration;

class m160531_101648_adding_top_nav_menu_items extends Migration
{
    public function up()
    {
        $menuItems = [
            [
                'icon' => 'fa fa-briefcase',
                'label' => 'Работа',
                'roles' => ['Admin', 'Supplier'],
                'items' => [
                    [
                        'icon' => 'fa fa-barcode',
                        'label' => 'Товары',
                        'action' => 'products',
                        'roles' => ['Admin', 'Supplier'],
                    ],
                ],
            ],
        ];
        Yii::$app->topMenu->addItems($menuItems);
    }

    public function down()
    {
        Yii::$app->topMenu->removeAll();
    }
}
