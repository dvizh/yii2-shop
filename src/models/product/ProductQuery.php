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
         return $this->andwhere("`available` = 'yes'");
    }
    
    public function category($childCategoriesIds)
    {
         return $this->andwhere(['category_id' => $childCategoriesIds]);
    }
}