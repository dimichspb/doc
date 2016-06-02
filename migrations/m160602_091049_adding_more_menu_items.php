<?php

use yii\db\Migration;

class m160602_091049_adding_more_menu_items extends Migration
{
    public function up()
    {
        Yii::$app->topMenu->removeAll();
        $menuItems = [
            [
                'icon' => 'fa fa-archive',
                'label' => 'Номенклатура',
                'roles' => ['Admin', 'Supplier'],
                'items' => [
                    [
                        'icon' => 'fa fa-barcode',
                        'label' => 'Товары',
                        'action' => 'products',
                        'roles' => ['Admin', 'Supplier'],
                    ],
                    [
                        'icon' => 'fa fa-calculator',
                        'label' => 'Цены',
                        'action' => 'prices',
                        'roles' => ['Admin', 'Supplier'],
                    ],
                ],

            ],
            [
                'icon' => 'fa fa-briefcase',
                'label' => 'Работа',
                'roles' => ['Admin', 'Supplier'],
                'items' => [
                    [
                        'icon' => 'fa fa-shopping-basket',
                        'label' => 'Запросы',
                        'action' => 'requests',
                        'roles' => ['Admin', 'Supplier'],
                    ],
                    [
                        'icon' => 'fa fa-file-text-o',
                        'label' => 'Предложения',
                        'action' => 'quotations',
                        'roles' => ['Admin', 'Supplier'],
                    ],
                    [
                        'icon' => 'fa fa-shopping-cart',
                        'label' => 'Заказы',
                        'action' => 'orders',
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
