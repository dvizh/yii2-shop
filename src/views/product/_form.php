<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dvizh\shop\models\Category;
use dvizh\shop\models\Producer;
use dvizh\gallery\widgets\Gallery;
use kartik\select2\Select2;
use dvizh\seo\widgets\SeoForm;

\dvizh\shop\assets\BackendAsset::register($this);
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <div class="row">
        <div class="col-lg-8 col-xs-6">
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
        <div class="col-lg-3 col-xs-4">
            <?= $form->field($model, 'slug')->textInput(['placeholder' => 'Не обязательно']) ?>
        </div>
        <div class="col-lg-1 col-xs-4">
            <?= $form->field($model, 'amount')->textInput() ?>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-xs-4">
            <?= $form->field($model, 'code')->textInput() ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'sku')->textInput() ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'barcode')->textInput() ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-2 col-xs-2 available-radio-block">
            <?php if($model->isNewRecord) $model->available = 'yes'; ?>
            <?= $form->field($model, 'available')->radioList(['yes' => 'Да','no' => 'Нет']); ?>
        </div>
        <div class="col-lg-2 col-xs-2 isnew-radio-block">
            <?php if($model->isNewRecord) $model->is_new = 'no'; ?>
            <?= $form->field($model, 'is_new')->radioList(['yes' => 'Да','no' => 'Нет']); ?>
        </div>
        <div class="col-lg-2 col-xs-2 ispopular-radio-block">
            <?php if($model->isNewRecord) $model->is_popular = 'no'; ?>
            <?= $form->field($model, 'is_popular')->radioList(['yes' => 'Да','no' => 'Нет']); ?>
        </div>
        <div class="col-lg-2 col-xs-2 ispromo-radio-block">
            <?php if($model->isNewRecord) $model->is_promo = 'no'; ?>
            <?= $form->field($model, 'is_promo')->radioList(['yes' => 'Да','no' => 'Нет']); ?>
        </div>
        <div class="col-lg-2 col-xs-2 sort-input-block">
            <?= $form->field($model, 'sort')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <?= $form->field($model, 'category_id')
                ->widget(Select2::classname(), [
                'data' => Category::buildTextTree(),
                'language' => 'ru',
                'options' => ['placeholder' => 'Выберите категорию ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            
            <?= $form->field($model, 'producer_id')
                ->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Producer::find()->all(), 'id', 'name'),
                'language' => 'ru',
                'options' => ['placeholder' => 'Выберите бренд ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-lg-6 col-xs-6">
            <?= $form->field($model, 'category_ids')
                ->label('Прочие категории')
                ->widget(Select2::classname(), [
                'data' => Category::buildTextTree(),
                'language' => 'ru',
                'options' => ['multiple' => true, 'placeholder' => 'Доп. категории ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>

    <?php echo $form->field($model, 'text')->widget(
        \yii\imperavi\Widget::className(),
        [
            'plugins' => ['fullscreen', 'fontcolor', 'video'],
            'options'=>[
                'minHeight' => 400,
                'maxHeight' => 400,
                'buttonSource' => true,
                'imageUpload' => Url::toRoute(['tools/upload-imperavi'])
            ]
        ]
    ) ?>

    <?= $form->field($model, 'short_text')->textInput(['maxlength' => true]) ?>

	<?=Gallery::widget(['model' => $model]); ?>

    <br />

    <?= SeoForm::widget([
        'model' => $model, 
        'form' => $form,
    ]); ?>
    
    <div class=" panel panel-default related-products-block">
        <div class="panel-heading"><strong>Связанные продукты</strong></div>
        <div class="panel-body">
            <?=\dvizh\relations\widgets\Constructor::widget(['model' => $model]);?>
        </div>
    </div>

    <?php if(isset($priceTypes)) { ?>
        <?php if($priceTypes) { ?>
            <h3>Цены</h3>
            <?php $i = 1; foreach($priceTypes as $priceType) { ?>
                <?= $form->field($priceModel, "[{$priceType->id}]price")->label($priceType->name); ?>
            <?php $i++; } ?>
        <?php } ?>
    <?php } ?>
    
    <div class="form-group shop-control">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if(!$model->isNewRecord) { ?>
            <a class="btn btn-default" href="<?=Url::toRoute(['product/delete', 'id' => $model->id]);?>" title="Удалить" aria-label="Удалить" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-trash"></span></a>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
