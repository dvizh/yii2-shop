<?php
namespace dvizh\shop\models;

use yii;

class PriceType extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_price_type}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['condition'], 'string'],
            [['sort'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'sort' => 'Сортировка',
            'condition' => 'Условие',
        ];
    }
}
