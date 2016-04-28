<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_item_to_role".
 *
 * @property integer $menu_item
 * @property string $role
 *
 * @property MenuItem $menuItem
 */
class MenuItemToRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item_to_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item'], 'integer'],
            [['role'], 'string', 'max' => 64],
            [['menu_item'], 'exist', 'skipOnError' => true, 'targetClass' => MenuItem::className(), 'targetAttribute' => ['menu_item' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_item' => 'Menu Item',
            'role' => 'Role',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItem()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'menu_item']);
    }
}
