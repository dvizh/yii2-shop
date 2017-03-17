<?php
use yii\helpers\Html;

$this->title = 'Обновить категорию: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
\dvizh\shop\assets\BackendAsset::register($this);
?>

<div class="category-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
