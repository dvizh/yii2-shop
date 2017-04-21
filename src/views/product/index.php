<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use dvizh\shop\models\Category;
use dvizh\shop\models\Producer;
use kartik\export\ExportMenu;

$this->title = 'Товары';
$this->params['breadcrumbs'][] = ['label' => 'Магазин', 'url' => ['/shop/default/index']];
$this->params['breadcrumbs'][] = $this->title;

\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="product-index">

    <div class="row">
        <div class="col-md-1">
            <?= Html::tag('button', 'Удалить', [
                'class' => 'btn btn-success dvizh-mass-delete',
                'disabled' => 'disabled',
                'data' => [
                    'model' => $dataProvider->query->modelClass,
                ],
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-2">
            <?php
            $gridColumns = [
                'id',
                'code',
                'category.name',
                'producer.name',
                'name',
                'price',
                'amount',
            ];

            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns
            ]);
            ?>
        </div>
    </div>
    
    <br style="clear: both;"></div>
    <?php
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => '\kartik\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 55px;']],
            'name',
            [
                'attribute' => 'images',
                'format' => 'images',
                'filter' => false,
                'content' => function ($model) {
                    if($image = $model->getImage()->getUrl('50x50')) {
                        return "<img src=\"{$image}\" class=\"thumb\" />";
                    }
                }
            ],
            'code',
            'amount',
            [
                'label' => 'Цена',
                'content' => function ($model) {
                    $return = '';

                    foreach($model->prices as $price) {
                        $return .= "<p class=\"productsMenuPrice\"><span title=\"{$price->name}\">{$price->price}</span></p>";
                    }

                    return $return;
                }
            ],
            [
                'attribute' => 'available',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'available',
                    ['no' => 'Нет', 'yes' => 'Да'],
                    ['class' => 'form-control', 'prompt' => 'Наличие']
                ),
            ],
            [
                'attribute' => 'category_id',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'category_id',
                    Category::buildTextTree(),
                    ['class' => 'form-control', 'prompt' => 'Категория']
                ),
                'value' => 'category.name'
            ],
            [
                'attribute' => 'producer_id',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'producer_id',
                    ArrayHelper::map(Producer::find()->orderBy('name')->all(), 'id', 'name'),
                    ['class' => 'form-control', 'prompt' => 'Производитель']
                ),
                'value' => 'producer.name'
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}']
        ],
    ]); ?>

</div>
