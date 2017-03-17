Yii2-shop
==========
Модуль представляет из себя бекенд для очень простого Интернет-магазина.

Установка
---------------------------------

```
php composer require dvizh/yii2-shop
```

Миграция:

```
php yii migrate --migrationPath=vendor/dvizh/yii2-micro-shop/migrations
```

Настройка
---------------------------------

В секцию modules конфига добавить:

```
    'modules' => [
        //..
        'shop' => [
            'class' => 'dvizh\microshop\Module',
            'adminRoles' => ['administrator'],
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