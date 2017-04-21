<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;

$this->title = 'Склады';
$this->params['breadcrumbs'][] = ['label' => 'Магазин', 'url' => ['/shop/default/index']];
$this->params['breadcrumbs'][] = $this->title;

\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="producer-index">
    
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
            <?= Html::a('Добавить склад', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-4">
            <?php
            $gridColumns = [
                'id',
                'name',
            ];

            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns
            ]);
            ?>
        </div>
    </div>

    <br style="clear: both;"></div>
    
    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => '\kartik\grid\CheckboxColumn'],
            ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 55px;']],
            
            'name',
            
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}']
        ],
    ]); ?>

</div>
