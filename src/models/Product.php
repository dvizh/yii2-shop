<?php
namespace dvizh\shop\models;

use Yii;
use yii\helpers\Url;
use dvizh\shop\models\product\ProductQuery;

class Product extends \yii\db\ActiveRecord implements \dvizh\relations\interfaces\Torelate, \dvizh\cart\interfaces\CartElement
{
    const PRICE_TYPE = 'p';

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
            'toCategory' => [
                'class' => 'voskobovich\manytomany\ManyToManyBehavior',
                'relations' => [
                    'category_ids' => 'categories',
                ],
            ],
            'seo' => [
                'class' => 'dvizh\seo\behaviors\SeoFields',
            ],
            'filter' => [
                'class' => 'dvizh\filter\behaviors\AttachFilterValues',
            ],
            'field' => [
                'class' => 'dvizh\field\behaviors\AttachFields',
            ],
        ];
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
            [['category_id', 'producer_id', 'sort', 'amount'], 'integer'],
            [['text', 'available', 'code', 'is_new', 'is_promo', 'is_popular'], 'string'],
            [['category_ids'], 'each', 'rule' => ['integer']],
            [['name'], 'string', 'max' => 200],
            [['short_text', 'slug'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код (актикул)',
            'category_id' => 'Главная категория',
            'producer_id' => 'Бренд',
            'name' => 'Название',
            'amount' => 'Остаток',
            'text' => 'Текст',
            'short_text' => 'Короткий текст',
            'images' => 'Картинки',
            'available' => 'В наличии',
            'is_new' => 'Новинка',
            'is_popular' => 'Популярное',
            'is_promo' => 'Акция',
            'sort' => 'Сортировка',
            'slug' => 'СЕО-имя',
            'amount_in_stock' => 'Количество на складах',
        ];
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function minusAmount($count, $moderator="false")
    {
        $this->amount = $this->amount-$count;
        $this->save(false);
        
        return $this;
    }
    
    public function plusAmount($count, $moderator="false")
    {
        $this->amount = $this->amount+$count;
        $this->save(false);
        
        return $this;
    }
    
    public function setPrice($price, $type = null)
    {
        if($priceModel = $this->getPriceModel($type)) {
            $priceModel->price = $price;

            return $priceModel->save(false);
        } elseif($type) {
            //Создаем новую цену
            if($typeModel = PriceType::findOne($type)) {
                $priceModel = new Price;
                $priceModel->item_id = $this->id;
                $priceModel->price = $price;
                $priceModel->type_id = $type;
                $priceModel->type = self::PRICE_TYPE;
                $priceModel->name = $typeModel->name;

                return $priceModel->save();
            }
        }
        
        return null;
    }
    
    public function getPriceModel($typeId = null)
    {
        if(!$typeId && !$typeId = yii::$app->getModule('shop')->defaultPriceTypeId) {
            return null;
        }

        return $this->getPrices()->andWhere(['type_id' => $typeId])->one();
    }
    
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['item_id' => 'id'])->where(['type' => self::PRICE_TYPE]);
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
        $options = [];
        
        if($filters = $this->getFilters()) {
            foreach($filters as $filter) {
                if($variants = $filter->variants) {
                    $options[$filter->id]['name'] = $filter->name;
                    foreach($variants as $variant) {
                        $options[$filter->id]['variants'][$variant->id] = $variant->value;
                    }
                }
            }
        }
        
        return $options;
        //return ['Цвет' => ['Красный', 'Белый', 'Синий'], 'Размер' => ['XXL']];
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getSellModel()
    {
        return $this;
    }

    public function getModifications()
    {
        $return = $this->hasMany(Modification::className(), ['product_id' => 'id'])->orderBy('sort DESC, id DESC');

        return $return;
    }

    public function getLink()
    {
        return Url::toRoute([yii::$app->getModule('shop')->productUrlPrefix, 'slug' => $this->slug]);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
             ->viaTable('{{%shop_product_to_category}}', ['product_id' => 'id']);
    }
    
    public function getProducer()
    {
        return $this->hasOne(Producer::className(), ['id' => 'producer_id']);
    }
    
    public function afterDelete()
    {
        parent::afterDelete();

        Modification::deleteAll(["product_id" => $this->id]);

        Price::deleteAll(["item_id" => $this->id]);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if(!empty($this->category_id) && !empty($this->id)) {
            if(!(new \yii\db\Query())
            ->select('*')
            ->from('{{%shop_product_to_category}}')
            ->where('product_id ='.$this->id.' AND category_id = '.$this->category_id)
            ->all()) {
                yii::$app->db->createCommand()->insert('{{%shop_product_to_category}}', [
                    'product_id' => $this->id,
                    'category_id' => $this->category_id,
                ])->execute();
            }
        }
    }
}
