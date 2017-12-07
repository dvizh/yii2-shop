<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;

$this->title = 'Типы цен';
$this->params['breadcrumbs'][] = ['label' => 'Магазин', 'url' => ['/shop/default/index']];
$this->params['breadcrumbs'][] = $this->title;

\dvizh\shop\assets\BackendAsset::register($this);
?>
<div class="price-type-index">

    <div class="row">
        <div class="col-md-2">
            <?= Html::a('Добавить новый тип', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-1">
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
    
    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => '\kartik\grid\CheckboxColumn'],
            ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 55px;']],
            
            'name',
            'sort',
            
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}']
        ],
    ]); ?>

</div>
