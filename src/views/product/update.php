<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use dosamigos\grid\columns\EditableColumn;

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => 'Товар', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="product-update">

    <ul class="nav nav-tabs">
        <li class="active product-tab-label"><a href="#product-product" data-toggle="tab">Карточка товара</a></li>
        <li class="options-tab-label"><a href="#product-modifications" data-toggle="tab">Модификации</a></li>
        <li class="prices-tab-label" ><a href="#product-prices" data-toggle="tab">Цены</a></li>
        <li class="filters-tab-label"><a href="#product-filters" data-toggle="tab">Фильтры</a></li>
        <li class="addfileds-tab-label"><a href="#product-fields" data-toggle="tab">Доп. поля</a></li>
    </ul>

    <div class="tab-content product-updater">
        <div class="tab-pane active" id="product-product">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>

        <div class="tab-pane" id="product-modifications">
            <?php if(yii::$app->session->hasFlash('modification-success-added')) { ?>
                <div class="alert alert-success" role="alert">
                    <?= yii::$app->session->getFlash('modification-success-added') ?>
                </div>
            <?php } ?>

            <?= GridView::widget([
                'dataProvider' => $modificationDataProvider,
                'filterModel' => $searchModificationModel,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn', 'options' => ['style' => 'width: 20px;']],
                    ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 25px;']],
                    [
                        'class' => EditableColumn::className(),
                        'attribute' => 'name',
                        'url' => ['/shop/modification/edit-field'],
                        'type' => 'text',
                        'editableOptions' => [
                            'mode' => 'inline',
                        ],
                        //'options' => ['style' => 'width: 75px;']
                    ],
                    [
                        'class' => EditableColumn::className(),
                        'attribute' => 'sort',
                        'url' => ['/shop/modification/edit-field'],
                        'type' => 'text',
                        'editableOptions' => [
                            'mode' => 'inline',
                        ],
                        //'options' => ['style' => 'width: 49px;']
                    ],
                    [
                        'class' => EditableColumn::className(),
                        'attribute' => 'available',
                        'url' => ['/shop/modification/edit-field'],
                        'type' => 'select',
                        'editableOptions' => [
                            'mode' => 'inline',
                            'source' => ['yes', 'no'],
                        ],
                        'filter' => false, /*Html::activeDropDownList(
                            $searchModel,
                            'available',
                            ['no' => 'Нет', 'yes' => 'Да'],
                            ['class' => 'form-control', 'prompt' => 'Наличие']
                        ),*/
                        //'contentOptions' => ['style' => 'width: 27px;']
                    ],
                    [
                        'class' => EditableColumn::className(),
                        'attribute' => 'price',
                        'label' => 'Цена',
                        'url' => ['/shop/modification/edit-field'],
                        'type' => 'text',
                        'editableOptions' => [
                            'mode' => 'inline',
                        ],
                        //'options' => ['style' => 'width: 40px;']
                    ],
                    [
                        'class' => EditableColumn::className(),
                        'attribute' => 'oldPrice',
                        'url' => ['/shop/modification/edit-field'],
                        'type' => 'text',
                        'label' => 'Старая цена',
                        'editableOptions' => [
                            'mode' => 'inline',
                        ],
                        //'options' => ['style' => 'width: 40px;']
                    ],
                    ['class' => 'yii\grid\ActionColumn', 'controller' => 'modification', 'template' => '{update} {delete}'],
                ],
            ]); ?>

            <p><a href="#modificationModal" data-toggle="modal" data-target="#modificationModal" class="btn btn-success add-product-modification">Добавить <span class="glyphicon glyphicon-plus add-price"></span></a></p>
            <div class="modal fade" id="modificationModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Товары</h4>
                        </div>
                        <div class="modal-body">
                            <iframe src="<?=Url::toRoute(['/shop/modification/add-popup', 'productId' => $model->id]);?>" id="modification-add-window"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="product-prices">
            <?php if($dataProvider->getCount()) { ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn', 'options' => ['style' => 'width: 20px;']],
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
                            //'options' => ['style' => 'width: 75px;']
                        ],
                        [
                            'class' => EditableColumn::className(),
                            'attribute' => 'sort',
                            'url' => ['price/edit-field'],
                            'type' => 'text',
                            'editableOptions' => [
                                'mode' => 'inline',
                            ],
                            //'options' => ['style' => 'width: 49px;']
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
                            'filter' => false, /*Html::activeDropDownList(
                                $searchModel,
                                'available',
                                ['no' => 'Нет', 'yes' => 'Да'],
                                ['class' => 'form-control', 'prompt' => 'Наличие']
                            ),*/
                            //'contentOptions' => ['style' => 'width: 27px;']
                        ],
                        [
                            'class' => EditableColumn::className(),
                            'attribute' => 'price',
                            'url' => ['price/edit-field'],
                            'type' => 'text',
                            'editableOptions' => [
                                'mode' => 'inline',
                            ],
                            //'options' => ['style' => 'width: 40px;']
                        ],
                        [
                            'class' => EditableColumn::className(),
                            'attribute' => 'price_old',
                            'url' => ['price/edit-field'],
                            'type' => 'text',
                            'editableOptions' => [
                                'mode' => 'inline',
                            ],
                            //'options' => ['style' => 'width: 40px;']
                        ],
                        ['class' => 'yii\grid\ActionColumn', 'controller' => 'price', 'template' => '{delete}',  'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 30px;']],
                    ],
                ]); ?>
            <?php } else { ?>
                <p style="color: red;">У товара нет цен.</p>
            <?php } ?>
            <?= $this->render('price/_form', [
                'model' => $priceModel,
                'productModel' => $model,
            ]) ?> 
        </div>

        <div class="tab-pane" id="product-filters">
            <?php if($filterPanel = \dvizh\filter\widgets\Choice::widget(['model' => $model])) { ?>
                <?=$filterPanel;?>
            <?php } else { ?>
                <p>В настоящий момент к категории данного товара не привязан ни один фильтр. Управлять фильтрами можно <?=Html::a('здесь', ['/filter/filter/index']);?>.</p>
            <?php } ?>
        </div>


        <div class="tab-pane" id="product-fields">
            <?php if($fieldPanel = \dvizh\field\widgets\Choice::widget(['model' => $model])) { ?>
                <?=$fieldPanel;?>
            <?php } else { ?>
                <p>Поля не заданы. Задать можно <?=Html::a('здесь', ['/field/field/index']);?>.</p>
            <?php } ?>
        </div>
    </div>
</div>
