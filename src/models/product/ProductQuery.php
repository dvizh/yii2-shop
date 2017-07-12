<?php
namespace dvizh\shop\models\product;

use dvizh\shop\models\Category;
use yii\db\ActiveQuery;

class ProductQuery extends ActiveQuery
{
    function behaviors()
    {
       return [
           'filter' => [
               'class' => 'dvizh\filter\behaviors\Filtered',
           ],
           'field' => [
               'class' => 'dvizh\field\behaviors\Filtered',
           ],
       ];
    }
    
    public function available()
    {
         return $this->andwhere("{{%shop_product%}}.available = 'yes'");
    }
    
    public function notAvailable()
    {
        return $this->andWhere("{{%shop_product%}}.available = 'no'");
    }
    
    public function category($childCategoriesIds)
    {
         return $this->andwhere(['category_id' => $childCategoriesIds]);
    }
}
