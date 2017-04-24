<?php
use yii\helpers\Html;
use yii\helpers\Url;
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
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle dvizh-mass-controls disabled" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-cog "></span>
                    <span class="caret "></span>
                </button>
                <ul class="dropdown-menu dvizh-model-control">
                    <li data-action="edit">
                        <a data-toggle="modal" data-target="#modal-control-model" data-model="<?= $dataProvider->query->modelClass ?>" class="dvizh-mass-edit" href="#">Редактиовать выбранные</a>
                    </li>
                    <li data-action="delete" >
                        <a  data-model="<?= $dataProvider->query->modelClass ?>" data-action="<?= Url::to(['/shop/product/mass-deletion']) ?>" class="dvizh-mass-delete" href="#">Удалить выбранные</a>
                    </li>
                </ul>
            </div>
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
<div class="modal fade" id="modal-control-model">
    <div class="modal-dialog modal-mass-update">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title">Выберите поля для редактирования</h3>
                <p>Вы можете редактировать одновременно несколько записей.
                    Выберете записи из списка выше, отметьте галочкой поля,
                    которые нужно отредактировать, и нажмите на кнопку
                    "Редактировать выбранные".</p>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#product-fields" data-toggle="tab">Поля</a></li>
                    <li><a href="#product-filters" data-toggle="tab">Фильтры</a></li>
                    <li><a href="#product-more-fields" data-toggle="tab">Доп. поля</a></li>
                </ul>
                <div class="tab-content product-updater">
                    <div class="tab-pane active" id="product-fields">
                        <?php if(!empty($model)) { ?>
                            <div class="row dvizh-mass-edit-filds">
                                <?php foreach ($model->attributeLabels() as $nameAttribute => $labelAttribute) { ?>
                                    <?php if($nameAttribute === 'amount_in_stock') continue; ?>
                                    <div class="col-sm-4">
                                        <?=  Html::checkbox($nameAttribute, true, ['label' => $labelAttribute, 'value' => $nameAttribute,]) ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <p class="cm-check-items-group">
                                <a class="cm-check-items cm-on" data-type="filds">Выбрать все</a> |
                                <a class="cm-check-items cm-off" data-type="filds">Снять выделение со всех</a>
                            </p>
                        <?php } ?>
                    </div>
                    <div class="tab-pane" id="product-filters">
                        <?php if(!empty($filters)) { ?>
                            <div class="row dvizh-mass-edit-filters">
                                <div class="col-sm-12">
                                    <b>Фильтры</b>
                                </div>
                                <?php foreach ($filters as $filter) { ?>
                                    <div class="col-sm-4">
                                        <?=  Html::checkbox($filter->slug, false, [
                                            'label' => $filter->name,
                                            'value' => $filter->id,
                                        ]) ?>
                                    </div>
                                <?php } ?>
                                <div class="col-sm-12">
                                    <p class="cm-check-items-group">
                                        <a class="cm-check-items cm-on" data-type="filters">Выбрать все</a> |
                                        <a class="cm-check-items cm-off" data-type="filters">Снять выделение со всех</a>
                                    </p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="tab-pane" id="product-more-fields">
                        <?php if(!empty($model)) { ?>
                            <div class="row dvizh-mass-edit-more-fields">
                                <div class="col-sm-12">
                                    <b>Поля</b>
                                </div>
                                <?php foreach ($model->getFields() as $filter) { ?>
                                    <div class="col-sm-4">
                                        <?=  Html::checkbox($filter->slug, false, [
                                            'label' => $filter->name,
                                            'value' => $filter->id,
                                        ]) ?>
                                    </div>
                                <?php } ?>
                                <div class="col-sm-12">
                                    <p class="cm-check-items-group">
                                        <a class="cm-check-items cm-on" data-type="more-fields">Выбрать все</a> |
                                        <a class="cm-check-items cm-off" data-type="more-fields">Снять выделение со всех</a>
                                    </p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" data-action="<?= Url::to(['/shop/product/mass-update']) ?>" data-model="<?= $dataProvider->query->modelClass ?>" class="btn btn-primary pistoll88-shop-edit-mass-form">Редактировать выбранные</button>
            </div>
        </div>
    </div>
</div>