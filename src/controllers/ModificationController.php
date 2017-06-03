<?php
namespace dvizh\shop\controllers;

use dvizh\shop\models\Modification;
use dvizh\shop\models\Product;
use dvizh\shop\models\ModificationToOption;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;

class ModificationController extends Controller
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

    public function actionAddPopup($productId)
    {
        $this->layout = 'mini';
        
        $model = new Modification;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($prices = yii::$app->request->post('Price')) {
                foreach($prices as $typeId => $price) {
                    $model->setPrice($price['price'], $typeId);
                }
            }

            if($filterValue = yii::$app->request->post('filterValue')) {
                ModificationToOption::deleteAll(['modification_id' => $model->id]);
                foreach($filterValue as $filterId => $variantId) {
                    $rel = new ModificationToOption;
                    $rel->modification_id = $model->id;
                    $rel->option_id = $filterId;
                    $rel->variant_id = $variantId;
                    $rel->save();
                }
            }

            yii::$app->session->setFlash('modification-success-added', 'Модификация успешно добавлена', false);            

            return '<script>parent.document.location = "'.Url::to(['/shop/product/update', 'id' => $model->product_id]).'";</script>';
        }

        $model->product_id = (int)$productId;
        $model->available = 'yes';

        $productModel = Product::findOne($productId);
        
        if (!$productModel) {
            throw new NotFoundHttpException('The requested product does not exist.');
        }

        return $this->render('create', [
            'model' => $model,
            'module' => $this->module,
            'productModel' => $productModel
        ]);
    }
    
    public function actionCreate()
    {
        $model = new Modification;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($prices = yii::$app->request->post('Price')) {
                foreach($prices as $typeId => $price) {
                    $model->setPrice($price['price'], $typeId);
                }
            }

            if($filterValue = yii::$app->request->post('filterValue')) {
                ModificationToOption::deleteAll(['modification_id' => $model->id]);
                foreach($filterValue as $filterId => $variantId) {
                    $rel = new ModificationToOption;
                    $rel->modification_id = $model->id;
                    $rel->option_id = $filterId;
                    $rel->variant_id = $variantId;
                    $rel->save();
                }
            }

            $this->redirect(Yii::$app->request->referrer);
        }
        
        $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($prices = yii::$app->request->post('Price')) {
                foreach($prices as $typeId => $price) {
                    $model->setPrice($price['price'], $typeId);
                }
            }

            if($filterValue = yii::$app->request->post('filterValue')) {
                ModificationToOption::deleteAll(['modification_id' => $model->id]);
                foreach($filterValue as $filterId => $variantId) {
                    $rel = new ModificationToOption;
                    $rel->modification_id = $model->id;
                    $rel->option_id = $filterId;
                    $rel->variant_id = $variantId;
                    $rel->save();
                }
            }

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            $productModel = $model->product;

            return $this->render('update', [
                'productModel' => $productModel,
                'module' => $this->module,
                'model' => $model,
            ]);
        }
    }
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $this->redirect(Yii::$app->request->referrer);
    }

    public function actionEditField()
    {
        $name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        $pk = unserialize(base64_decode(Yii::$app->request->post('pk')));
        $model = new Modification;
        $model::editField($pk, $name, $value);
    }

    protected function findModel($id)
    {
        $model = new Modification;
        
        if (($model = $model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
