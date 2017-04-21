<?php
namespace dvizh\shop;

use yii\base\BootstrapInterface;
use yii;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if(!$app->has('shop')) {
            $app->set('shop', ['class' => 'dvizh\shop\Shop']);
        }
    }
}