<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dvizh\shop\widgets\massUpdate\MassUpdate;

$this->title = 'Массовое редактирование';
\dvizh\shop\assets\BackendAsset::register($this);
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
    <?= Html::tag('a', 'Отмена', ['class' => 'btn', 'href' => 'index']); ?>
</div>
<?= MassUpdate::widget([
    'form' => $form,
    'models' => $models,
    'allEntities' => $allEntities,
    'entitiesName' => $entitiesName,
]) ?>

<?php foreach ($entitiesName as $entity) { ?>
    <?= Html::hiddenInput($entity, $postData[$entity]); ?>
<?php } ?>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
<?= Html::tag('a', 'Отмена', ['class' => 'btn', 'href' => 'index']); ?>
<?php $form = ActiveForm::end(); ?>

