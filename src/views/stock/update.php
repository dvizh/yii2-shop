<?php
use yii\helpers\Html;

$this->title = 'Обновить склад: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Склады', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="producer-update">
    <div class="shop-menu">
        <?=$this->render('../parts/menu');?>
    </div>
    
    <?= $this->render('_form', [
        'model' => $model,
        'activeStaffers' => $activeStaffers,
    ]) ?>

    <?php if($fieldPanel = \dvizh\field\widgets\Choice::widget(['model' => $model])) { ?>
        <div class="block">
            <h2>Прочее</h2>
            <?=$fieldPanel;?>
        </div>
    <?php } ?>
    
</div>
