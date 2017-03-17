<?php
namespace dvizh\shop\assets;

use yii\web\AssetBundle;

class CreateIncomingAsset extends AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    
    public $js = [
        'js/createincoming.js',
    ];

    public $css = [
        
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }

}
