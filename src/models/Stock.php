<?php
namespace dvizh\shop\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dvizh\shop\models\StockToProduct;
use yii\db\ActiveQuery;

class Stock extends \yii\db\ActiveRecord
{
    //public $user_ids = '';
    
    function behaviors()
    {
        return [
            'field' => [
                'class' => 'dvizh\field\behaviors\AttachFields',
            ],
            [
                'class' => \voskobovich\manytomany\ManyToManyBehavior::className(),
                'relations' => [
                    'user_ids' => 'users',
                ],
            ],
        ];
    }
	
    public static function tableName()
    {
        return '{{%shop_stock}}';
    }
    
    public function rules()
    {
        return [
            [['name', 'address'], 'required'],
            [['text', 'address', 'name'], 'string'],
            [['user_ids'], 'each', 'rule' => ['integer']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Адрес',
            'name' => 'Название',
            'text' => 'Текст',
            'user_ids' => 'Сотрудники',
        ];
    }
	
    public function getProductAmount($productId){
        if($amount = StockToProduct::find('amount')->where(['product_id' => $productId, 'stock_id' => $this->id])->one()) {
            return $amount->amount;
        } else {
            return 0;
        }
    }
	
    public function getProducts()
    {
        return $this->hasMany(StockToProduct::className(), ['stock_id' => 'id']);
    }
    
    public function getUsers()
    {
        $userModel = Yii::$app->getModule('shop')->userModel;
        
        return $this->hasMany($userModel::className(), ['id' => 'user_id'])->viaTable('shop_stock_to_user', ['stock_id' => 'id']);
    }
    
    public static function editField($id, $value, $productId) 
    {
        $stock = Stock::findOne($id);
        
        if($productAmount = $stock->getProducts()->where(['product_id' => $productId, 'stock_id' => $id])->one()){
            $productAmount->amount = $value;
            $productAmount->save();
        } else {
            $productAmount = new StockToProduct();
            $productAmount->amount = $value;
            $productAmount->product_id = $productId;
            $productAmount->stock_id = $id;
            $productAmount->save();
        }
    }
    
    public function getAmount($productId)
    {
        $amount = $amount = $this->getProducts();
		
        if($amount && $amount = $amount->where(['product_id' => $productId])->one()) {
            return $amount->amount;
        } else {
            return 0;
        }
    }
    
    public function minusAmount($productId, $count)
    {
        $amount = $amount = $this->getProducts();
        
        if($amount !== false && $amount = $amount->where(['product_id' => $productId])->one()) {
            $amount->amount = $amount->amount-$count;
            $amount->save();
            
            return $amount->amount;
        } else {
            return false;
        }
    }
    
    public function plusAmount($productId, $count)
    {
        $amount = $amount = $this->getProducts();
        
        if($amount && $amount = $amount->where(['product_id' => $productId])->one()) {
            $amount->amount = $amount->count+$count;
            $amount->save();
            
            return $amount->amount;
        } else {
            return false;
        }
    }
    
    public static function getAvailable($userId = null)
    {
        $userId = $userId ? $userId : \Yii::$app->user->id;
        $stockIds = StockToUser::find()->where(['user_id' => $userId])->all();
        $stockIds = ArrayHelper::getColumn($stockIds, 'user_id');
        
        return Stock::find()->where(['id' => $stockIds])->all();
    }
}
