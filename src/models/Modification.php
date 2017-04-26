<?php
namespace dvizh\shop\models;

use Yii;
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

    public function setPrice($price, $type = null)
    {
        if($priceModel = $this->getPriceModel($type)) {
            $priceModel->price = $price;
            return $priceModel->save(false);
        } else {
            if($typeModel = PriceType::findOne($type)) {
                $priceModel = new Price;
                $priceModel->product_id = $this->id;
                $priceModel->price = $price;
                $priceModel->type_id = $type;
                $priceModel->type = 'm';
                $priceModel->name = $typeModel->name;

                return $priceModel->save();
            }

        }

        return false;
    }

    public function getPriceModel($typeId = null)
    {
        if(!$typeId && !$typeId = yii::$app->getModule('shop')->defaultPriceTypeId) {
            return null;
        }

        return $this->getPrices()->where(['type_id' => $typeId])->one();
    }

    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['product_id' => 'id'])->where(['type' => 'm']);
    }

    public function getPrice($type = null)
    {
        if($callable = yii::$app->getModule('shop')->priceCallable) {
            return $callable($this);
        }

        if($price = $this->getPriceModel($type)) {
            return $price->price;
        }

        return null;
    }

    public function getOldprice($type = null)
    {
        if($price = $this->getPriceModel($type)) {
            return $price->price_old;
        }

        return null;
    }

    public function getProduct()
    {
        return $this;
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
