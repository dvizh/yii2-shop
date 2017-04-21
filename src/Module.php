<?php
namespace dvizh\shop;

use yii;

class Module extends \yii\base\Module
{
    public $adminRoles = ['admin', 'superadmin'];
    public $priceCallable = null;
    public $defaultPriceTypeId = 1;
    public $oneC = null;
    public $userModel = null;
    public $users = [];

    public $categoryUrlPrefix = '/shop/category/view';
    public $productUrlPrefix = '/shop/product/view';

    const EVENT_PRODUCT_CREATE = 'create_product';
    const EVENT_PRODUCT_DELETE = 'delete_product';
    const EVENT_PRODUCT_UPDATE = 'update_product';
    
    public function init()
    {
        if(!$this->userModel) {
            if($user = yii::$app->user->getIdentity()) {
                $this->userModel = $user::className();
            }
        }
        
        if(is_callable($this->users)) {
            $func = $this->users;
            $this->users = $func();
        }

        parent::init();
    }
}
