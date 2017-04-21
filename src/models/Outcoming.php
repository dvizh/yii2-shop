<?php
namespace dvizh\shop\models;

use Yii;

class Outcoming extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%shop_outcoming}}';
    }

    public function rules()
    {
        return [
            [['stock_id', 'product_id'], 'required'],
            [['date', 'stock_id', 'product_id', 'user_id', 'order_id', 'count'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата',
            'content' => 'Содержание заказа',
        ];
    }
}
