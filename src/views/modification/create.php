<?php
$this->title = 'Добавить модификацию';

\dvizh\shop\assets\ModificationConstructAsset::register($this);
?>
<div class="product-modification-create">

    <?= $this->render('_form', [
        'model' => $model,
        'productModel' => $productModel,
        'module' => $module,
    ]) ?>

</div>
