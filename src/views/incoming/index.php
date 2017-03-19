<?php

$this->title = 'Поступления';
$this->params['breadcrumbs'][] = $this->title;

\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="incoming-index">

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
