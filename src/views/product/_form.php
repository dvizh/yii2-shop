<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dvizh\shop\models\Category;
use kartik\select2\Select2;

\dvizh\shop\assets\BackendAsset::register($this);
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
        <div class="col-lg-2 col-xs-6">
            <?= $form->field($model, 'code')->textInput() ?>
        </div>
        <div class="col-lg-2 col-xs-6">
            <?= $form->field($model, 'amount')->textInput() ?>
        </div>
        <div class="col-lg-2 col-xs-6">
            <?= $form->field($model, 'price')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 col-xs-6">
            <?= $form->field($model, 'category_id')
                ->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Category::find()->all(), 'id', 'name'),
                'language' => 'ru',
                'options' => ['placeholder' => 'Выберите категорию ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-lg-2 col-xs-2">
            <?php if($model->isNewRecord) $model->available = 'yes'; ?>
            <?= $form->field($model, 'available')->radioList(['yes' => 'Да','no' => 'Нет']); ?>
        </div>
        <div class="col-lg-2 col-xs-2">
            <?php if($model->isNewRecord) $model->is_new = 'no'; ?>
            <?= $form->field($model, 'is_new')->radioList(['yes' => 'Да','no' => 'Нет']); ?>
        </div>
        <div class="col-lg-2 col-xs-2">
            <?php if($model->isNewRecord) $model->is_popular = 'no'; ?>
            <?= $form->field($model, 'is_popular')->radioList(['yes' => 'Да','no' => 'Нет']); ?>
        </div>
        <div class="col-lg-2 col-xs-2">
            <?php if($model->isNewRecord) $model->is_promo = 'no'; ?>
            <?= $form->field($model, 'is_promo')->radioList(['yes' => 'Да','no' => 'Нет']); ?>
        </div>
        <div class="col-lg-2 col-xs-2">
            <?= $form->field($model, 'sort')->textInput() ?>
        </div>
    </div>

    <?php echo $form->field($model, 'text')->textArea() ?>
    
    <div class="form-group shop-control">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if(!$model->isNewRecord) { ?>
            <a class="btn btn-default" href="<?=Url::toRoute(['product/delete', 'id' => $model->id]);?>" title="Удалить" aria-label="Удалить" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-trash"></span></a>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
