<?php
namespace dvizh\shop\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use yii;

class ShowPrice extends \yii\base\Widget
{
    public $model = NULL;
    public $htmlTag = 'span';
    public $cssClass = '';

    public function init()
    {
        \dvizh\shop\assets\WidgetAsset::register($this->getView());

        return parent::init();
    }

    public function run()
    {
        $js = 'dvizh.modificationconstruct.dvizhShopUpdatePriceUrl = "' .Url::toRoute(['/shop/tools/get-modification-by-options']). '";';

        $this->getView()->registerJs($js);

        return Html::tag(
                $this->htmlTag,
                $this->model->getPrice(),
                ['class' => "dvizh-shop-price dvizh-shop-price-{$this->model->id} {$this->cssClass}"]
            );
    }
}
