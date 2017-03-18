<?php
namespace dvizh\shop\models;

use dvizh\shop\models\product\ProductQuery;

class Product extends \yii\db\ActiveRecord  implements \dvizh\dic\interfaces\entity\SoldGoods
{
    public function getItemId()
    {
        return $this->id;
    }

    public static function getById($id) : ?\dvizh\dic\interfaces\entity\SoldGoods
    {
        return self::findOne($id);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getBasePrice()
    {
        return $this->price;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getModelName()
    {
        return self::className();
    }

    public function getOptions() : array
    {
        return [];
    }

    public function getDescription()
    {
        return '';
    }

    public function setModelName($modelName)
    {

    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setItemId($itemId)
    {

    }

    public function setCount($count)
    {

    }

    public function setBasePrice($basePrice)
    {

    }

    public function setPrice($price)
    {

    }

    public function setOptions($options)
    {

    }

    public function setDescription($description)
    {

    }

    public function saveData()
    {
        return $this->save();
    }

    public static function tableName()
    {
        return '{{%shop_product}}';
    }
    
    public static function Find()
    {
        $return = new ProductQuery(get_called_class());
        $return = $return->with('category');
        
        return $return;
    }
    
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['category_id', 'sort', 'amount'], 'integer'],
            [['price'], 'double'],
            [['text', 'available', 'code', 'is_new', 'is_promo', 'is_popular'], 'string'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код (актикул)',
            'category_id' => 'Главная категория',
            'name' => 'Название',
            'amount' => 'Остаток',
            'price' => 'Цена',
            'text' => 'Текст',
            'images' => 'Картинка',
            'available' => 'В наличии',
            'is_new' => 'Новинка',
            'is_popular' => 'Популярное',
            'is_promo' => 'Акция',
            'sort' => 'Сортировка',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
