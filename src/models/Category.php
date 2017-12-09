<?php
namespace dvizh\shop\models;

use Yii;
use dvizh\shop\models\category\CategoryQuery;
use yii\helpers\Url;

class Category extends \yii\db\ActiveRecord
{
    function behaviors()
    {
        return [
            'images' => [
                'class' => 'dvizh\gallery\behaviors\AttachImages',
                'mode' => 'single',
            ],
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
            ],
            'seo' => [
                'class' => 'dvizh\seo\behaviors\SeoFields',
            ],
            'field' => [
                'class' => 'dvizh\field\behaviors\AttachFields',
            ],
        ];
    }
    
    public static function tableName()
    {
        return '{{%shop_category}}';
    }
    
    static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    public function rules()
    {
        return [
            [['parent_id', 'sort'], 'integer'],
            [['name'], 'required'],
            [['text', 'code'], 'string'],
            [['name', 'code', 'slug'], 'string', 'max' => 55],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Родительская категория',
            'name' => 'Имя категории',
            'slug' => 'Сео имя',
            'text' => 'Описание',
            'image' => 'Картинка',
            'sort' => 'Сортировка',
            'description' => 'Описание',
        ];
    }
    
    public static function buildTree($parent_id = null)
    {
        $return = [];
        
        if(empty($parent_id)) {
            $categories = Category::find()->where('parent_id = 0 OR parent_id is null')->orderBy('sort DESC')->asArray()->all();
        } else {
            $categories = Category::find()->where(['parent_id' => $parent_id])->orderBy('sort DESC')->asArray()->all();
        }
        
        foreach($categories as $level1) {
            $return[$level1['id']] = $level1;
            $return[$level1['id']]['childs'] = self::buildTree($level1['id']);
        }
        
        return $return;
    }

    public static function buildTextTree($groupCategories = [], $id = null, $level = 1, &$treeCategories = [])
    {
        if($id) {

            if (isset($groupCategories[$id])) {

                $prefix = str_repeat('--', $level);
                $level++;
                foreach($groupCategories[$id] as $category){
                    $treeCategories[$category['id']] = $prefix.$category['name'];
                    self::buildTextTree($groupCategories, $category['id'], $level, $treeCategories);
                }
            }

            return $treeCategories;

        } else {
            $treeCategories = [];
            $groupedCategories = [];
            $categories = Yii::$app->db->createCommand('SELECT id, parent_id, name FROM shop_category ORDER BY id ASC')->queryAll();

            if (!is_array($categories)) {
                return false;
            }

            foreach ($categories as $key => $category) {
                $groupedCategories[$category['parent_id']][] = $category;
            }

            foreach ($groupedCategories[''] as $groupedCategory) {
                $treeCategories[$groupedCategory['id']] = '' . $groupedCategory['name'];
                $treeCategories = self::buildTextTree($groupedCategories, $groupedCategory['id'], 1, $treeCategories);
            }

            return $treeCategories;
        }
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
             ->viaTable('{{%shop_product_to_category}}', ['category_id' => 'id'])->available();
    }
    
    public function getChilds()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }
    
    public function getLink()
    {
        return Url::toRoute([yii::$app->getModule('shop')->categoryUrlPrefix, 'slug' => $this->slug]);
    }
}
