<?php
namespace dvizh\shop\models;

use Yii;
use yii\helpers\Url;
use dvizh\shop\models\Category;
use dvizh\shop\models\Price;
use dvizh\shop\models\product\ProductQuery;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

class Modification extends \yii\db\ActiveRecord implements \dvizh\cart\interfaces\CartElement
{
    function behaviors()
    {
        return [
            'images' => [
                'class' => 'dvizh\gallery\behaviors\AttachImages',
                'mode' => 'gallery',
            ],
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
            ],
            'relations' => [
                'class' => 'dvizh\relations\behaviors\AttachRelations',
                'relatedModel' => 'dvizh\shop\models\Product',
                'inAttribute' => 'related_ids',
            ],
            'seo' => [
                'class' => 'dvizh\seo\behaviors\SeoFields',
            ],
            'time' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    public static function tableName()
    {
        return '{{%shop_product_modification}}';
    }
    
    public function rules()
    {
        return [
            [['name', 'product_id'], 'required'],
            [['sort', 'amount', 'product_id'], 'integer'],
            [['price', 'price_old'], 'number'],
            [['name', 'available', 'code', 'create_time', 'update_time', 'filter_values'], 'string'],
            [['name'], 'string', 'max' => 55],
            [['slug'], 'string', 'max' => 88]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Товар',
            'name' => 'Название',
            'code' => 'Код (актикул)',
            'price' => 'Цена',
            'price_old' => 'Старая цена',
            'images' => 'Картинки',
            'available' => 'В наличии',
            'sort' => 'Сортировка',
            'slug' => 'СЕО-имя',
            'amount' => 'Остаток',
            'create_time' => 'Дата создания',
            'update_time' => 'Дата обновления',
            'filter_values' => 'Сочетание значений фильтров',
        ];
    }
    
    public function getFiltervariants()
    {
        $return = [];
        
        if($selected = unserialize($this->filter_values)) {
            foreach($selected as $filter => $value) {
                $return[] = $value;
            }
        }
        
        return $return;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function minusAmount($count)
    {
        $this->amount = $this->amount-$count;
        
        return $this->save(false);
    }
    
    public function plusAmount($count)
    {
        $this->amount = $this->amount+$count;
        
        return $this->save(false);
    }
    
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    
    public function getCartId()
    {
        return $this->id;
    }
    
    public function getCartName()
    {
        return $this->name;
    }
    
    public function getCartPrice()
    {
        return $this->price;
    }

    public function getCartOptions()
    {
        return '';
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getSellModel()
    {
        return $this;
    }
    
    public function getPrices()
    {
        $return = $this->hasMany(Price::className(), ['product_id' => 'id'])->orderBy('price ASC');

        return $return;
    }
    
    public function getPrice($type = 'lower')
    {
        $price = $this->hasOne(Price::className(), ['product_id' => 'product_id']);
        
        if($type == 'lower') {
            $price = $price->orderBy('price ASC')->one();
        } elseif($type) {
            $price = $price->where(['type_id' => $type])->one();
        } elseif($defaultType = yii::$app->getModule('shop')->getPriceTypeId($this)) {
            $price = $price->where(['type_id' => $defaultType])->one();
        } else {
            $price = $price->orderBy('price DESC')->one();
        }
        
        if($price) {
            return $price->price;
        }
        
        return null;
    }
    
    public function beforeValidate()
    {
        if($filterValue = yii::$app->request->post('filterValue')) {
            $filter_values = [];
            foreach($filterValue as $filterId => $variantId) {
                $filter_values[$filterId] = $variantId;
            }
            $this->filter_values = serialize($filter_values);
        } else {
            $this->filter_values = serialize([]);
        }

        return parent::beforeValidate();
    }
    
    public static function editField($id, $name, $value) 
    {
        $setting = Modification::findOne($id);
        $setting->$name = $value;
        $setting->save();
    }
}
