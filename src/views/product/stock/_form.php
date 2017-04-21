<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductOption */
/* @var $form yii\widgets\ActiveForm */
?>
<a href="#" class="btn btn-success" onclick="$('.product-add-price-form').toggle(); return false;">Добавить <span class="glyphicon glyphicon-plus add-price"></span></a>
<div class="product-add-price-form" style="display: none;">
    
    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['price/create'])]); ?>

    <?= $form->field($model, 'product_id')->textInput(['type' => 'hidden', 'value' => $productModel->id])->label(false) ?>
    
    <?= $form->field($model, 'name')->textInput(['value' => $model->name?$model->name:'Основная цена']) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'code')->textInput() ?>
    
    <?= $form->field($model, 'amount')->textInput(['value' => 0]) ?>
    
    <?php $model->available = 'yes'; ?>
    
    <?= $form->field($model, 'available')->radioList(['yes' => 'Да','no' => 'Нет']); ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>