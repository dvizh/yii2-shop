<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;

$this->title = 'Производители';
$this->params['breadcrumbs'][] = ['label' => 'Магазин', 'url' => ['/shop/default/index']];
$this->params['breadcrumbs'][] = $this->title;

\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="producer-index">
    
    <div class="row">
        <div class="col-md-2">
            <?= Html::a('Создать производителя', ['create'], ['class' => 'btn btn-success']) ?>
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
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle dvizh-mass-controls disabled" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-cog "></span>
                    <span class="caret "></span>
                </button>
                <ul class="dropdown-menu dvizh-model-control">
                    <li data-action="delete" >
                        <a  data-model="<?= $dataProvider->query->modelClass ?>" data-action="<?= Url::to(['/shop/product/mass-deletion']) ?>" class="dvizh-mass-delete" href="#">Удалить выбранные</a>
                    </li>
                </ul>
            </div>
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
            [
				'attribute' => 'image',
				'format' => 'image',
                'filter' => false,
				'content' => function ($image) {
                    if($image = $image->getImage()->getUrl('50x50')) {
                        return "<img src=\"{$image}\" class=\"thumb\" />";
                    }
				}
			],
            
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}']
        ],
    ]); ?>

</div>
