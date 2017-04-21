<?php

namespace dvizh\shop\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dvizh\shop\models\incoming\IncomingSearch;
use dvizh\shop\models\Incoming;
use dvizh\shop\models\Product;

class IncomingController extends Controller
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
        $searchModel = new IncomingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->orderBy('id DESC');
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreate()
    {
        $model = new Incoming;

        if ($post = Yii::$app->request->post()) {
            
            $productModel = new Product;
            
            foreach($post['element'] as $id => $count) {
                $model = new Incoming;
                $model->date = time();
                $model->content = Html::encode(yii::$app->request->post('content'));
                $model->amount = $count;
                
                if($product = $productModel::findOne($id)) {
                    $product->plusAmount($count);
                    $model->product_id = $id;
                }
                
                if($prices = $post['price'][$id]) {
                    foreach($prices as $typeTypeId => $price) {
                        if($price) {
                            $product->setPrice($price, $typeTypeId);
                            $model->price = $price;
                        }
                    }
                }
                
                if($model->save()) {
                    \Yii::$app->session->setFlash('success', 'Поступление успешно добавлено.');
                }
            }

            return $this->redirect(['create', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
}
