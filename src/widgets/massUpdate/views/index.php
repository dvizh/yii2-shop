<?php

use kartik\select2\Select2;
use dvizh\gallery\widgets\Gallery;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

?>

<h3>№ <?= $number ?></h3>
<div class="col-sm-12 mass-update-row">
    <div class="id-item-mass">
        <b>ID товара:</b> <?= $model->id ?>
        <b>Название:</b> <?= $model->name ?>
    </div>
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#fields-<?= $model->id ?>">Основные поля</a></li>
            <?php if (ArrayHelper::isIn('text', $attributes)) { ?>
                <li><a data-toggle="tab" href="#text-<?= $model->id ?>">Текст</a></li>
            <?php } ?>
            <?php if ($fields) { ?>
                <li><a data-toggle="tab" href="#additional-fields-<?= $model->id ?>">Дополнительные поля</a></li>
            <?php } ?>
            <?php if ($filters) { ?>
                <li><a data-toggle="tab" href="#filters-<?= $model->id ?>">Фильтры</a></li>
            <?php } ?>
            <?php if (ArrayHelper::isIn('prices', $otherEntities)) { ?>
                <li><a data-toggle="tab" href="#prices-<?= $model->id ?>">Цены</a></li>
            <?php } ?>
            <?php if (ArrayHelper::isIn('images', $otherEntities)) { ?>
                <li><a data-toggle="tab" href="#images-<?= $model->id ?>">Картинки</a></li>
            <?php } ?>
        </ul>

        <div class="tab-content mass-item-product">
            <div id="fields-<?= $model->id ?>" class="tab-pane fade in active">
                <div class="row">

                    <?php if (ArrayHelper::isIn('name', $attributes)) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[$model->id]" . "name")->textInput() ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('sort', $attributes)) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[$model->id]" . "sort")->textInput() ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('slug', $attributes)) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[$model->id]" . "slug")->textInput(['placeholder' => 'Не обязательно']) ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('amount', $attributes)) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[$model->id]" . "amount")->textInput() ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('code', $attributes)) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[$model->id]" . "code")->textInput() ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('category_id', $attributes)) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[$model->id]" . "category_id")
                                ->widget(Select2::classname(), [
                                    'data' => $treeCategories,
                                    'language' => 'ru',
                                    'options' => ['placeholder' => 'Выберите категорию ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('producer_id', $attributes)) { ?>
                        <div class="col-sm-4">
                            <?= $form->field($model, "[$model->id]" . "producer_id")
                                ->widget(Select2::classname(), [
                                    'data' => $producersId,
                                    'language' => 'ru',
                                    'options' => ['placeholder' => 'Выберите бренд ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('available', $attributes)) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[$model->id]" . "available")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('is_new', $attributes)) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[$model->id]" . "is_new")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('is_popular', $attributes)) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[$model->id]" . "is_popular")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('is_promo', $attributes)) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[$model->id]" . "is_promo")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('is_custom', $attributes)) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[$model->id]" . "is_custom")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>

                    <?php if (ArrayHelper::isIn('is_sale', $attributes)) { ?>
                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <?= $form->field($model, "[$model->id]" . "is_sale")->radioList(['yes' => 'Да', 'no' => 'Нет']); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if (ArrayHelper::isIn('text', $attributes)) { ?>
                <div id="text-<?= $model->id ?>" class="tab-pane fade">
                    <div class="col-sm-12">
                        <?= $form->field($model, "[$model->id]" . "text")->widget(
                            \yii\imperavi\Widget::className(),
                            [
                                'plugins' => ['fullscreen', 'fontcolor', 'video', 'table'],
                                'options' => [
                                    'minHeight' => 250,
                                    'maxHeight' => 250,
                                    'buttonSource' => true,
                                    'imageUpload' => Url::toRoute(['tools/upload-imperavi'])
                                ]
                            ]
                        ) ?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($fields) { ?>
                <div id="additional-fields-<?= $model->id ?>" class="tab-pane fade">

                    <?= \dvizh\field\widgets\Choice::widget(['model' => $model, 'includeId' => $fields]); ?>
                </div>
            <?php } ?>
            <?php if ($filters) { ?>
                <div id="filters-<?= $model->id ?>" class="tab-pane fade">
                    <?= \dvizh\filter\widgets\Choice::widget(['model' => $model, 'includeId' => $filters]) ?>
                </div>
            <?php } ?>
            <?php if (ArrayHelper::isIn('prices', $otherEntities)) { ?>
                <div id="prices-<?= $model->id ?>" class="tab-pane fade">
                    <?= $this->render('part/_prices', ['dataProviderPrices' => $dataProviderPrices]) ?>
                </div>
            <?php } ?>
            <?php if (ArrayHelper::isIn('images', $otherEntities)) { ?>
                <div id="images-<?= $model->id ?>" class="tab-pane fade">
                    <?= Gallery::widget(['model' => $model]); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
