<?php
use yii\helpers\Html;

$this->title = 'Создать тип цен';
$this->params['breadcrumbs'][] = ['label' => 'Типы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="price-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
