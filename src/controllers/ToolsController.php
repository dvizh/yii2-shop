<?php
namespace dvizh\shop\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use dvizh\shop\models\Product;

class ToolsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
			'actions' => ['upload-imperavi', 'sync'],
                        'roles' => $this->module->adminRoles,
                    ]
                ]
            ],
        ];
    }
	
    public function actionGetModificationByOptions()
    {
        if($product = Product::findOne(yii::$app->request->post('productId'))) {
            foreach($product->getModifications()->andWhere(['available' => 'yes'])->all() as $modification) {
                $suitable = true;
                foreach(yii::$app->request->post('options') as $optionId => $variantId) {
                    if(!in_array($variantId, $modification->filtervariants)) {
                        $suitable = false;
                        break;
                    }
                }

                if($suitable) {
                    return json_encode([
                        'modification' => [
                            'id' => $modification->id,
                            'price' => $modification->price,
                            'name' => $modification->name,
                            'code' => $modification->code,
                            'sku' => $modification->sku,
                            'barcode' => $modification->barcode,
                            'amount' => $modification->amount,
                            'sort' => $modification->sort,
                        ],
                        'product_price' => $product->price,
                    ]);
                }
            }

            return json_encode([]);
        }
        else {
            return json_encode([]);
        }
    }

    public function actionSync($syncToken = null)
    {
        if($syncToken != $this->module->syncToken) {
            return;
        }

        set_time_limit(0);
        $productService = new Product;
        $categoryService = new Category;
		$producerService = new Producer;
        
        $path = $this->module->oneC['importFolder'];
        if($ftp = $this->module->oneC['importFTP']) {
            preg_match("/ftp:\/\/(.*?):(.*?)@(.*?)/Usi", $ftp, $match); 

            $conn = ftp_connect($match[3]);
            $callCount = 0;
            if(ftp_login($conn, $match[1], $match[2])) {
                ftp_chdir($conn, 'webdata');
                ftp_get($conn, $path.'/import0_1.xml', 'import0_1.xml', FTP_ASCII);
                ftp_get($conn, $path.'/offers0_1.xml', 'offers0_1.xml', FTP_ASCII);
                $folders = ftp_nlist($conn, "import_files");
                ftp_chdir($conn, 'import_files');
                
                foreach($folders as $folder) {
                    $ourFolder = $path . '/import_files/' . $folder;
                    
                    if(!file_exists($ourFolder)) {
                        mkdir($ourFolder, 0777, true);
                    }

                    $files = ftp_nlist($conn, $folder);
                    
                    if(!ftp_chdir($conn, $folder)) {
                        echo 'Error with ' . $ourFolder . '/' . $file . "...<br />";
                    }

                    foreach($files as $file) {
                        if(in_array(strtolower(end(explode('.', $file))), array('jpg', 'jpeg', 'png', 'gif'))) {
                            echo 'Try ' . $ourFolder . '/' . $file . "...<br />";
                            
                            if(!ftp_get($conn, $ourFolder . '/' . $file, $file, FTP_ASCII)) {
                                echo 'error 1';
                            }
                            
                            echo 'Download ' . $ourFolder . '/' . $file . "...<br />";
                            
                            $callCount++;
                            
                            if($callCount > 40) {
                                echo 'FTP close... and open....<br />';
                                ftp_close($conn);
                                $conn = ftp_connect($match[3]);
                                ftp_login($conn, $match[1], $match[2]);
                                ftp_chdir($conn, 'webdata');
                                ftp_chdir($conn, 'import_files');
                                ftp_chdir($conn, $folder);
                                $callCount = 0;
                            }
                            
                            flush();
                        }
                    }
                    
                    ftp_chdir($conn, '..');
                }
            }
        }
        
        $importFiles = glob("$path/import*.xml");
        $offerFiles = glob("$path/offers*.xml");

        foreach($importFiles as $key => $importFile) {
            echo "Importfile $importFile...<br />";
            
            $data = simplexml_load_file($importFile);
            $offers = simplexml_load_file($offerFiles[$key]);
            $this->parseCategory($data->Классификатор->Группы->Группа);
            
            $prices = [];
            foreach($offers->ПакетПредложений->Предложения->Предложение as $offer) {
                foreach($offer->Цены->Цена as $price) {
                    $priceType = (string)$price->ИдТипаЦены;
                    $prices[(string)$offer->Ид][$priceType] = (int)$price->ЦенаЗаЕдиницу;
                }
            }
            
            foreach($data->Каталог->Товары->Товар as $product) {
                $groupId = (string)$product->Группы->Ид;
                
				$category = $categoryService::find()->where(['code' => $groupId])->one();

				$producer = null;
				
				if($product->Изготовитель) {
					if($producerId = (string)$product->Изготовитель->Ид) {
						if(!$producer = $producerService::find(['code' => $producerId])->one()) {
							$producer = new $producerService;
							$producer->name = (string)$product->Изготовитель->Наименование;
							$producer->save();
						}
					}
				}
				
				$code = (string)$product->Ид;
				$amount = (int)$product->БазоваяЕдиница->Пересчет->Единица;
				$name = (string)$product->Наименование;
				
				if(!$shopProduct = $productService::find()->where(['code' => $code])->one()) {
					$shopProduct = new $productService; 
				}

				$shopProduct->amount = $amount;
				$shopProduct->name = $name;
				$shopProduct->code = $code;
				$shopProduct->amount = $amount;

				if($category) {
					$shopProduct->category_id = $category->id;
				}
                
				if($producer) {
					$shopProduct->producer_id = $producer->id;
				}
				
				$shopProduct->save();

				echo $shopProduct->id.'+<br />';
				
                if($productPrices = $prices[$shopProduct->code]) {
                    foreach($productPrices as $priceId => $price) {
                        if($price) {
                            $priceId = $this->module->oneC['pricesTypes'][$priceId];
                            if($priceId) {
                                echo 'Set price ' . $priceId . ' - ' . $price . '.....<br />';

                                $shopProduct->setPrice($price, $priceId);
                            }
                        }
                    }
                }
 
                 if($product->Картинка) {
                    if($shopProduct->hasImage()) {
                        foreach($shopProduct->getImages() as $imageModel) {
                            $imageModel->delete();
                        }
                    }
                 }
 
				foreach($product->Картинка as $image) {
                    $image = $path . '/' . (string)$image;

                    if(file_exists($image)) {
                        if(sizeof($image) < 1942177) {
                            echo "Attach image $image<br />";
                            
                            $shopProduct->attachImage($image);
                        }
                    }
				}
            }
        }
        
        echo 'Done.';
    }
    
    private function parseCategory($groups, $parentId = 0)
    {
        $categoryService = new Category;
        
        foreach($groups as $group) {
            $code = (string)$group->Ид;
            if(!$category = $categoryService::find()->where(['code' => $code])->one()) {
                $category = new $categoryService; 
                $category->name = (string)$group->Наименование;
                $category->code = $code;
                $category->parent_id = $parentId;
            } else {
                $category->name = (string)$group->Наименование;
	        $category->parent_id = $parentId;
            }
            
            $category->save();
            
            if($subGroups = $group->Группы->Группа) {
                $this->parseCategory($subGroups, $category->id);
            }
            
        }
    }
    
    public function actions()
    {
        return [
            'upload-imperavi' => [
                'class' => 'trntv\filekit\actions\UploadAction',
                'fileparam' => 'file',
                'responseUrlParam'=> 'filelink',
                'multiple' => false,
                'disableCsrf' => true
            ]
        ];
    }
}
