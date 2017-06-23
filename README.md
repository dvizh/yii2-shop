Yii2-shop
==========
Модуль представляет из себя бекенд для Интернет-магазина.

![yii2-shop](https://cloud.githubusercontent.com/assets/8104605/15448447/751a647a-1f7b-11e6-87e7-c7354306f10e.png)

В состав входит возможность управлять (CRUD):

* Категориями
* Производителями
* Товарами
* Ценами
* Фильтрами (опциями)
* Дополнительными полями

Если есть необходимость, можно также подтянуть мои другие модули:

* [dvizh/yii2-cart](https://github.com/dvizh/yii2-cart) - корзина
* [dvizh/yii2-order](https://github.com/dvizh/yii2-order) - заказ
* [dvizh/yii2-promocode](https://github.com/dvizh/yii2-promocode) - промокоды

Установка
---------------------------------

Рекомендую устанавливать в common/modules/dvizh:

```
git clone https://github.com/dvizh/yii2-shop.git
```

И подключать через psr-4 секцию composer.json:

```
"autoload": {
    "psr-4": {
        "dvizh\\shop\\": "common/modules/dvizh/yii2-shop"
    }
}
```

Модуль зависит от многих других пакетов, скопируйте их из моего в свой composer.json в секцию require. После этого не забудьте выполнить composer update и миграции каждого модуля.

Если хотите установить в папку vendor через composer и ничего не менять потом, устанавливайте стандартно: 'php composer require dvizh/yii2-shop "@dev"' в командной строке.

Миграция:

```
php yii migrate --migrationPath=vendor/dvizh/yii2-shop/src/migrations
```

Настройка
---------------------------------

В конфиг (скорее всего, bootstrap.php) добавить:

```
Yii::setAlias('@storageUrl','/frontend/web/images');
```

В секцию modules конфига добавить:

```
    'modules' => [
        //..
        'shop' => [
            'class' => 'dvizh\shop\Module',
            'adminRoles' => ['administrator', 'superadmin', 'admin'],
            'defaultPriceTypeId' => 1, //Цена по умолчанию
        ],
        'filter' => [
            'class' => 'dvizh\filter\Module',
            'adminRoles' => ['administrator'],
            'relationFieldName' => 'category_id',
            'relationFieldValues' =>
                function() {
                    return \dvizh\shop\models\Category::buildTextTree();
                },
        ],
        'field' => [
            'class' => 'dvizh\field\Module',
            'relationModels' => [
                'dvizh\shop\models\Product' => 'Продукты',
                'dvizh\shop\models\Category' => 'Категории',
                'dvizh\shop\models\Producer' => 'Производители',
            ],
            'adminRoles' => ['administrator'],
        ],
        'relations' => [
            'class' => 'dvizh\relations\Module',
            'fields' => ['code'],
        ],
        'gallery' => [
            'class' => 'dvizh\gallery\Module',
            'imagesStorePath' => dirname(dirname(__DIR__)).'/storage/web/images/store',
            'imagesCachePath' => dirname(dirname(__DIR__)).'/storage/web/images/cache',
            'graphicsLibrary' => 'GD',
            'placeHolderPath' => dirname(dirname(__DIR__)).'/storage/web/images/placeHolder.png',
        ],
        //..
    ]
```

В секцию components:

```
    'components' => [
        //..
        'fileStorage' => [
            'class' => '\trntv\filekit\Storage',
            'baseUrl' => '@storageUrl/source',
            'filesystem'=> function() {
                $adapter = new \League\Flysystem\Adapter\Local(dirname(dirname(__DIR__)).'/frontend/web/images/source');
                return new League\Flysystem\Filesystem($adapter);
            },
        ],
        //..
    ]
```

Использование
---------------------------------

* ?r=shop/product - продукты
* ?r=shop/category - категории
* ?r=shop/producer - производители
* ?r=filter/filter - фильтры (опции)
* ?r=field/field - доп. поля

Виджеты
---------------------------------

* dvizh\shop\widgets\ShowPrice - передается 'model', выводит цену. Связан с dvizh\cart\widgets\ChangeOptions через jQuery триггер и может определять, какая модификация выбрана и динамически менять цену.

Пример карточки товара со всеми виджетами магазина и корзины, которые работают сообща и динамически меняют данные друг-друга.


```
<?php
use dvizh\shop\widgets\ShowPrice;
use dvizh\cart\widgets\BuyButton;
use dvizh\cart\widgets\TruncateButton;
use dvizh\cart\widgets\CartInformer;
use dvizh\cart\widgets\ElementsList;
use dvizh\cart\widgets\ChangeCount;
use dvizh\cart\widgets\ChangeOptions;

$product = \dvizh\shop\models\Product::findOne(1); //from controller
?>
<div class="site-index">
    <h1><?=$product->name;?></h1>
    
    <h2>Shop</h2>
    <div class="block row">
        <h3>ShowPrice</h3>
        <?=ShowPrice::widget(['model' => $product]);?>
    </div>
    
    <h2>Cart</h2>
    <div class="block row">
        <div class="col-md-3">
            <h3>ChangeCount</h3>
            <?=ChangeCount::widget(['model' => $product]);?>
        </div>
        <div class="col-md-3">
            <h3>ChangeOptions</h3>
            <?=ChangeOptions::widget(['model' => $product]);?>
        </div>
        <div class="col-md-3">
            <h3>BuyButton</h3>
            <?=BuyButton::widget(['model' => $product]);?>
        </div>
        <div class="col-md-3">
            <h3>TruncateButton</h3>
            <?=TruncateButton::widget();?>
        </div>
        <div class="col-md-3">
            <h3>CartInformer</h3>
            <?=CartInformer::widget();?>
        </div>
        <div class="col-md-3">
            <h3>ElementsList</h3>
            <?=ElementsList::widget(['type' => 'dropdown']);?>
        </div>
    </div>
    
    <style>
        .block {
            border: 2px solid blue;
        }
    </style>
    
</div>

```
