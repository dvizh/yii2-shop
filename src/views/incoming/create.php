<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use dvizh\shop\models\Product;

$this->title = 'Новое поступление';
$this->params['breadcrumbs'][] = $this->title;

\dvizh\shop\assets\CreateIncomingAsset::register($this);
\dvizh\shop\assets\BackendAsset::register($this);

$this->registerJs(
    "
    $('.incoming-delete').on('click', function() {
        
    });
    
    $('.incoming select[name=incomingproduct]').on('change', function() {
        $('.new-input').val($(this).val()).change();
    });"
);
?>
<div class="incoming-create">
    <div class="row">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-10">
            <?=$this->render('../parts/menu');?>
        </div>
    </div>
    
    <?php if(Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    

    
    <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <div class="row">
                <div class="col-lg-6">
                    <div class="incoming">
                        <?= Select2::widget([
                            'data' => ArrayHelper::map(Product::find()->all(), 'id', 'name'),
                            'name' => 'incomingproduct',
                            'language' => 'ru',
                            'options' => ['placeholder' => 'Выберите товар ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    </div>
                </div>
                <div class="col-lg-6"><input class="new-input" data-info-service="<?=Url::toRoute(['/shop/product/product-info']);?>" type="text" value="" placeholder="Код или артикул + Enter" style="width: 300px;" /></div>
            </div>
        </div>
        <div id="incoming-list" style="width: 800px; padding: 20px;">
        </div>
        
        
        <div class="form-group">
            <textarea name="content" class="form-control" placeholder="Комментарий"></textarea>
        </div>
        
        <div class="form-group">
            <?= Html::submitButton('Добавить поступление', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>