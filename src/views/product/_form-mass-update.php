<?php
use dosamigos\grid\columns\EditableColumn;
use kartik\select2\Select2;
use dvizh\gallery\widgets\Gallery;
use dvizh\shop\models\Category;
use dvizh\shop\models\price\PriceSearch;
use dvizh\shop\models\Producer;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Массовое редактирование продукции';
\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="product-mass">
    <?php if (isset($models)) { ?>
        <div class="product-form-mass">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                <?= Html::tag('a', 'Отмена', ['class' => 'btn', 'href' => 'index']) ?>
            </div>

            <?php foreach ($models as $key => $model) { ?>
                <div class="row">
                    <div class="id-item-mass">
                        <b>Название:</b> <?= $model->name ?>
                        <b>ID товара:</b> <?= $model->id ?>
                    </div>
                    <?php if (!empty($filters)) { ?>
                        <div class="col-sm-12">
                            <?php if ($filterPanel = \dvizh\filter\widgets\Choice::widget(['model' => $model, 'includeId' => $filters])) { ?>
                                <?= $filterPanel; ?>
                            <?php } else { ?>
                                <p>В настоящий момент к категории данного товара не привязан ни один фильтр. Управлять
                                    фильтрами можно <?= Html::a('здесь', ['/filter/filter/index']); ?>.</p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($fields)) { ?>
                        <div class="col-sm-12">
                            <?php if ($fieldPanel = \dvizh\field\widgets\Choice::widget(['model' => $model, 'includeId' => $fields])) { ?>
                                <?= $fieldPanel; ?>
                            <?php } else { ?>
                                <p>Поля не заданы. Задать можно <?= Html::a('здесь', ['/field/field/index']); ?>.</p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($attributes['name'])) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[{$model->id}][Product]name")->textInput() ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($attributes['sort'])) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[{$model->id}][Product]sort")->textInput() ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($attributes['slug'])) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[{$model->id}][Product]slug")->textInput(['placeholder' => 'Не обязательно']) ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($attributes['amount'])) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[{$model->id}][Product]amount")->textInput() ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($attributes['code'])) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[{$model->id}][Product]code")->textInput() ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <?php if (isset($attributes['category_id'])) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'category_id')
                                ->widget(Select2::classname(), [
                                    'data' => Category::buildTextTree(),
                                    'language' => 'ru',
                                    'options' => ['placeholder' => 'Выберите категорию ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($attributes['producer_id'])) { ?>
                        <div class="col-sm-4">
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
                    <?php } ?>
                </div>


                <div class="row">
                    <?php if (isset($attributes['available'])) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[{$model->id}][Product]available")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($attributes['is_new'])) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[{$model->id}][Product]is_new")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($attributes['is_popular'])) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[{$model->id}][Product]is_popular")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($attributes['is_promo'])) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[{$model->id}][Product]is_promo")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($attributes['is_custom'])) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[{$model->id}][Product]is_custom")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($attributes['is_sale'])) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[{$model->id}][Product]is_sale")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>
                </div>

                <?php if (isset($attributes['images'])) { ?>
                    <?= Gallery::widget(['model' => $model]); ?>
                <?php } ?>

                <?php if (isset($attributes['text'])) { ?>
                    <?php echo $form->field($model, "[{$model->id}][Product]text")->widget(
                        \yii\imperavi\Widget::className(),
                        [
                            'plugins' => ['fullscreen', 'fontcolor', 'video', 'table'],
                            'options' => [
                                'minHeight' => 400,
                                'maxHeight' => 400,
                                'buttonSource' => true,
                                'imageUpload' => Url::toRoute(['tools/upload-imperavi'])
                            ]
                        ]
                    ) ?>
                <?php } ?>
                <div class="table-responsive">
                    <?php if (isset($attributes['price'])) { ?>
                        <?php
                        $searchModel = new PriceSearch();
                        $priceModel = new \dvizh\shop\models\Price();
                        $typeParams = Yii::$app->request->queryParams;
                        $typeParams['StockSearch']['product_id'] = $model->id;
                        $typeParams = Yii::$app->request->queryParams;
                        $typeParams['PriceSearch']['product_id'] = $model->id;
                        $typeParams['ModificationSearch']['product_id'] = $model->id;
                        $dataProvider = $searchModel->search($typeParams);
                        ?>
                        <?php if ($dataProvider->getCount()) { ?>
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 25px;']],
                                    [
                                        'class' => EditableColumn::className(),
                                        'attribute' => 'name',
                                        'url' => ['price/edit-field'],
                                        'type' => 'text',
                                        'filter' => false,
                                        'editableOptions' => [
                                            'mode' => 'inline',
                                        ],
                                    ],
                                    [
                                        'class' => EditableColumn::className(),
                                        'attribute' => 'sort',
                                        'url' => ['price/edit-field'],
                                        'type' => 'text',
                                        'editableOptions' => [
                                            'mode' => 'inline',
                                        ],
                                    ],
                                    [
                                        'class' => EditableColumn::className(),
                                        'attribute' => 'available',
                                        'url' => ['price/edit-field'],
                                        'type' => 'select',
                                        'editableOptions' => [
                                            'mode' => 'inline',
                                            'source' => ['yes', 'no'],
                                        ],
                                        'filter' => false,
                                    ],
                                    [
                                        'class' => EditableColumn::className(),
                                        'attribute' => 'price',
                                        'url' => ['price/edit-field'],
                                        'type' => 'text',
                                        'editableOptions' => [
                                            'mode' => 'inline',
                                        ],
                                    ],
                                    [
                                        'class' => EditableColumn::className(),
                                        'attribute' => 'price_old',
                                        'url' => ['price/edit-field'],
                                        'type' => 'text',
                                        'editableOptions' => [
                                            'mode' => 'inline',
                                        ],
                                    ],
                                    ['class' => 'yii\grid\ActionColumn', 'controller' => 'price', 'template' => '{delete}', 'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 30px;']],
                                ],
                            ]); ?>
                        <?php } else { ?>
                            <p style="color: red;">У товара нет цен.</p>
                        <?php } ?>
                    <?php } ?>
                </div>
                <hr>
            <?php } ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            <?= Html::tag('a', 'Отмена', ['class' => 'btn', 'href' => 'index']) ?>
            <?php $form = ActiveForm::end(); ?>
        </div>
    <?php } ?>
</div>
