<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Поступления';
$this->params['breadcrumbs'][] = ['label' => 'Магазин', 'url' => ['/shop/default/index']];
$this->params['breadcrumbs'][] = $this->title;

\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="category-index">
    <div class="row">
        <div class="col-md-2">
            <?= Html::a('Создать поступление', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-10">

        </div>
    </div>

    <?=\kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 55px;']],
            'product.name',
            'content',
            'amount',
            'price',
            [
                'attribute' => 'date',
                'value' => function($model) {
                    return date('d.m.Y H:i', $model->date);
                }
            ],
        ],
    ]);?>

</div>
