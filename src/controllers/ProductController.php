<?php
namespace dvizh\shop\controllers;

use Yii;
use dvizh\shop\models\Modification;
use dvizh\shop\models\Product;
use dvizh\shop\models\PriceType;
use dvizh\shop\models\Price;
use dvizh\shop\models\price\PriceSearch;
use dvizh\shop\models\product\ProductSearch;
use dvizh\shop\models\stock\StockSearch;
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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'filters' => $filters,
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

    public function actionFormMassUpdate()
    {
        $session = Yii::$app->session;
        if (Yii::$app->request->post()) {
            $models = Yii::$app->request->post('Product');
            if(!empty($models)) {
                foreach ($models as $key => $model) {
                    $modeFind = $session['massUpdate']['model'];
                    $newModel = $modeFind::findOne(['id' => $key]);
                    if (!empty($newModel)) {
                        $newModel->load($model);
                        $newModel->save();
                    }
                }
            }
            $session->remove('massUpdate');
            $this->redirect(['index']);
        }

        $session = Yii::$app->session;
        if (isset($session['massUpdate'])) {
            $massUpdate = $session['massUpdate'];
            if (isset($massUpdate['modelId']) && isset($massUpdate['attributes']) && isset($massUpdate['model'])) {
                $modelId = $massUpdate['modelId'];
                $attributes = $massUpdate['attributes'];
                $filters = $massUpdate['filters'];
                $fields = $massUpdate['fields'];
                $modelName = $massUpdate['model'];
                $models = $modelName::findAll($modelId);
                array_push($attributes, 'images');
                unset($attributes['amount_in_stock']);

                return $this->render('_form-mass-update', [
                    'modelId' => $modelId,
                    'attributes' => $attributes,
                    'filters' => $filters,
                    'fields' => $fields,
                    'modelName' => $modelName,
                    'models' => $models,
                ]);
            }
        }
    }

    public function actionMassUpdate()
    {
        $filters = NULL;
        $fields = NULL;
        $postData = \Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = $postData['model'];
        $modelId = $postData['modelId'];
        $attributes = $postData['attributes'];
        if(isset($postData['filters'])) $filters = $postData['filters'];
        if(isset($postData['fields'])) $fields = $postData['fields'];

        if(!empty($modelId) && !empty($model) && !empty($attributes)) {
            $ranks = $model::findAll($modelId);
            $session = Yii::$app->session;
            $session['massUpdate'] = [
                'model' => $model,
                'modelId' => $modelId,
                'attributes' => $attributes,
                'filters' => $filters,
                'fields' => $fields,
            ];
            if(!empty($ranks)) {
                return $this->redirect(['form-mass-update']);
            }
        }

        return  false;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $typeParams = Yii::$app->request->queryParams;
        $typeParams['StockSearch']['product_id'] = $id;
        $StockSearch = new StockSearch();
        $StockDataProvider = $StockSearch->search($typeParams);

        $searchModel = new PriceSearch();
        $typeParams = Yii::$app->request->queryParams;
        $typeParams['PriceSearch']['product_id'] = $id;
        $dataProvider = $searchModel->search($typeParams);
        $priceModel = new Price;

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
                'StockSearch' => $StockSearch,
                'StockDataProvider' => $StockDataProvider,
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
