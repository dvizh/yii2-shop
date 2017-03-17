<?php
namespace dvizh\shop\models\product;

use dvizh\shop\models\Category;
use yii\db\ActiveQuery;

class ProductQuery extends ActiveQuery
{
    public function available()
    {
         return $this->andwhere("`available` = 'yes'");
    }
    
    public function category($childCategoriesIds)
    {
         return $this->andwhere(['category_id' => $childCategoriesIds]);
    }
    
    public function getTotalPrice()
    {
        return $this->sum("price*amount");
    }
    
    public function getTotalAmount()
    {
        return $this->sum("amount");
    }
}