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
        if($modifications = $this->model->modifications) {
            $json = [];
            
            foreach($modifications as $modification) {
                $json[$modification->id] = [
                    'product_id' => $modification->product_id,
                    'name' => $modification->name,
                    'code' => $modification->code,
                    'price' => $modification->price,
                    'amount' => $modification->amount,
                    'filter_value' => $modification->filtervariants,
                ];
            }
            
            $js = 'dvizh.modificationconstruct.modifications = '.json_encode($json).';';
            
            $this->getView()->registerJs($js);
        }
        
        
        return Html::tag(
                $this->htmlTag,
                $this->model->getPrice(),
                ['class' => "dvizh-shop-price dvizh-shop-price-{$this->model->id} {$this->cssClass}"]
            );
    }
}
