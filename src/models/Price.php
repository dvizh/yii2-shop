<?php
namespace dvizh\shop\models;

use yii;

class Price extends \yii\db\ActiveRecord implements \dvizh\cart\interfaces\CartElement 
{
    
    public static function tableName()
    {
        return '{{%shop_price}}';
    }

    public function rules()
    {
        return [
            [['name', 'item_id'], 'required'],
            [['name', 'available', 'code'], 'string', 'max' => 100],
            [['price', 'price_old'], 'number'],
            [['item_id', 'amount', 'type_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'type_id' => 'Тип',
            'item_id' => 'Продукт',
            'price' => 'Цена',
            'price_old' => 'Старая цена',
            'code' => 'Артикул',
            'available' => 'Наличие',
            'amount' => 'Остаток',
            'sort' => 'Приоритет',
        ];
    }

    public function minusAmount($count)
    {
        $this->amount = $this->product->amount-$count;
        
        return $this->save(false);
    }
    
    public function plusAmount($count)
    {
        $this->amount = $this->product->amount+$count;
        
        return $this->save(false);
    }
    
    public function getCartId() {
        return $this->id;
    }
    
    public function getCartName() {
        return $this->product->name;
    }
    
    public function getCartPrice() {
        return $this->price;
    }

    public function getCartOptions()
    {
        return '';
    }
    
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'item_id']);
    }
    
    public static function editField($id, $name, $value) 
    {
        $setting = Price::findOne($id);
        $setting->$name = $value;
        $setting->save();
    }
}
