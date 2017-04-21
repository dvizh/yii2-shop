<?php
use yii\helpers\Html;

$this->title = 'Обновить производителя: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Производители', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="producer-update">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php if($fieldPanel = \dvizh\field\widgets\Choice::widget(['model' => $model])) { ?>
        <div class="block">
            <h2>Прочее</h2>
            <?=$fieldPanel;?>
        </div>
    <?php } ?>
    
</div>
