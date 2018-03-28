<?php
namespace dvizh\shop\controllers;

use Yii;
use dvizh\shop\models\Modification;
use dvizh\shop\models\Product;
use dvizh\shop\models\PriceType;
use dvizh\shop\models\Price;
use dvizh\shop\models\price\PriceSearch;
use dvizh\shop\models\product\ProductSearch;
use dvizh\shop\events\ProductEvent;
use dvizh\shop\models\modification\ModificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles,
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'edittable' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = null;
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $models = $dataProvider->models;
        if(!empty($models)) $model = array_shift($models);
        $filters = \dvizh\filter\models\Filter::find()->all();
        $ignoreAttribute =  ['amount_in_stock', 'images'];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'filters' => $filters,
            'ignoreAttribute' => $ignoreAttribute,
        ]);
    }

    public function actionCreate()
    {
        $model = new Product;
        $priceModel = new Price;

        $priceTypes = PriceType::find()->orderBy('sort DESC')->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if($prices = yii::$app->request->post('Price')) {
                foreach($prices as $typeId => $price) {
                    $model->setPrice($price['price'], $typeId);
                }
            }

            $module = $this->module;
            $productEvent = new ProductEvent(['model' => $model]);
            $this->module->trigger($module::EVENT_PRODUCT_CREATE, $productEvent);

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'priceModel' => $priceModel,
                'priceTypes' => $priceTypes,
            ]);
        }
    }

    public function actionMassUpdate()
    {
        $partForm = null;
        $allEntities = null;
        $entitiesName = ['modelsId', 'attributes', 'otherEntities', 'filters', 'fields'];
        $postData = \Yii::$app->request->post();
        foreach ($entitiesName as $name) {
            $allEntities[$name] = explode(',', $postData[$name]);
        }
        $models = Product::findAll($allEntities['modelsId']);
        if(isset($postData['Product'])) {
            foreach ($models as $model) {
                $newData = $postData['Product'][$model->id];
                foreach ($newData as $name => $value) {
                    $model->$name = $value;
                }
                $model->save();
            }
            $models = Product::findAll($allEntities['modelsId']);
        }

        return $this->render('_form-mass-update', [
            'models' => $models,
            'allEntities' => $allEntities,
            'entitiesName' => $entitiesName,
            'postData' => $postData,
        ]);
    }

    public function actionUpdate($id)
    {
        $priceModel = new Price;
        $searchModel = new PriceSearch();
        $model = $this->findModel($id);
        $typeParams = Yii::$app->request->queryParams;
        $typeParams['PriceSearch']['item_id'] = $id;
        $dataProvider = $searchModel->search($typeParams);

        $searchModificationModel = new ModificationSearch();
        $typeParams['ModificationSearch']['product_id'] = $id;
        $modificationDataProvider = $searchModificationModel->search($typeParams);
        $modificationModel = new Modification;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $module = $this->module;
            $productEvent = new ProductEvent(['model' => $model]);
            $this->module->trigger($module::EVENT_PRODUCT_UPDATE, $productEvent);

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'module' => $this->module,
                'modificationModel' => $modificationModel,
                'searchModificationModel' => $searchModificationModel,
                'modificationDataProvider' => $modificationDataProvider,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'priceModel' => $priceModel,
            ]);
        }
    }

    public function actionDelete($id)
    {
        if($model = $this->findModel($id)) {
            $this->findModel($id)->delete();

            $module = $this->module;
            $productEvent = new ProductEvent(['model' => $model]);
            $this->module->trigger($module::EVENT_PRODUCT_DELETE, $productEvent);
        }

        return $this->redirect(['index']);
    }

    public function actionProductInfo()
    {
        $productCode = (int)yii::$app->request->post('productCode');

        $model = new Product;

        if($model = $model::find()->where('code=:code OR id=:code', [':code' => $productCode])->one()) {
            $json = [
                'status' => 'success',
                'name' => $model->name,
                'code' => $model->code,
                'id' => $model->id,
            ];
        } else {
            $json = [
                'status' => 'fail',
                'message' => yii::t('order', 'Not found')
            ];
        }

        die(json_encode($json));
    }

    protected function findModel($id)
    {
        $model = new Product;

        if (($model = $model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMassDeletion()
    {
        $postData = \Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = $postData['model'];
        $modelId = $postData['modelId'];
        if(!empty($modelId)) {
            $ranks = $model::findAll($modelId);
            if(!empty($ranks)) {
                foreach ($ranks as $rank) {
                    $rank->delete();
                }
                return  true;
            }
        }

        return  false;
    }
}
