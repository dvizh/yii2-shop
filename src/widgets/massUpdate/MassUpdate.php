<?php

namespace dvizh\shop\widgets\massUpdate;

use dvizh\shop\models\Category;
use dvizh\shop\models\Price;
use dvizh\shop\models\Producer;
use dvizh\shop\widgets\massUpdate\assets\WidgetAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class MassUpdate extends \yii\base\Widget
{
    public $form = null;
    public $models = null;
    public $allEntities = null;
    public $entitiesName = null;
    public $partForm = null;

    public function init()
    {
        return parent::init();
        WidgetAsset::register($this->getView());

        return true;
    }

    public function run()
    {
        $treeCategories = Category::buildTextTree();
        foreach ($this->allEntities['filters'] as $filter) {
            if ($filter === '') {
                $filters = null;
                break;
            }
            $filters[$filter] = $filter;
        }
        foreach ($this->allEntities['fields'] as $field) {
            if ($field === '') {
                $fields = null;
                break;
            }
            $fields[$field] = $field;
        }

        foreach ($this->models as $number => $model) {

            $this->partForm .= $this->render('index', [
                'treeCategories' => $treeCategories,
                'modelsId' => $this->allEntities['modelsId'],
                'attributes' => $this->allEntities['attributes'],
                'otherEntities' => $this->allEntities['otherEntities'],
                'filters' => $filters,
                'fields' => $fields,
                'entitiesName' => $this->entitiesName,
                'model' => $model,
                'form' => $this->form,
                'number' => $number + 1,
                'producersId' => $this->getProducersId(),
                'dataProviderPrices' => $this->getDataProvider($this->getPrices($model->id)),
            ]);
        }

        return $this->partForm;
    }

    private function getProducersId()
    {
        $producers = Producer::find()->all();

        return ArrayHelper::map($producers, 'id', 'name');
    }

    private function getPrices($id)
    {
        return Price::find()->where(['item_id' => $id]);
    }

    private function getDataProvider($query)
    {
        return new ActiveDataProvider(['query' => $query]);
    }
}
