<?php
namespace dvizh\shop\models;

use dvizh\shop\models\product\ProductQuery;

class Product extends \yii\db\ActiveRecord implements \dvizh\dic\interfaces\cart\CartElement
{
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

    public function setPrice($price)
    {
        $this->price = $price;
        $this->save(false);
        
        return $this;
    }
    
    public function minusAmount($count)
    {
        $this->amount = $this->amount-$count;
        $this->save(false);
        
        return $this;
    }
    
    public function plusAmount($count)
    {
        $this->amount = $this->amount+$count;
        $this->save(false);
        
        return $this;
    }

    public function getProduct()
    {
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCount()
    {
        return 1;
    }

    public function getPrice()
    {
        return $this->price;
    }
    
    public function getOptions()
    {
        $options = [];

        return $options;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function getModelName()
    {
        return self::className();
    }

    public function getSellModel()
    {
        return $this;
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getModel()
    {
        return $this;
    }
}
