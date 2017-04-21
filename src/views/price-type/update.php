<?php
use yii\helpers\Html;

$this->title = 'Обновить тип цен: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="price-type-update">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
