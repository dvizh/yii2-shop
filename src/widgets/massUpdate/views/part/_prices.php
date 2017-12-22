<?php
use yii\grid\GridView;
use dosamigos\grid\columns\EditableColumn;
?>

<div class="table-responsive">
    <?php if ($dataProviderPrices) { ?>
        <?= GridView::widget([
            'dataProvider' => $dataProviderPrices,
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
        <p>Цены не указаны</p>
    <?php } ?>
</div>