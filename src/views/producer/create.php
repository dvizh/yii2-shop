<?php
$this->title = 'Создать производителя';
$this->params['breadcrumbs'][] = ['label' => 'Производители', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="producer-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
