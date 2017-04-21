<?php
namespace dvizh\shop\models\product;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dvizh\shop\models\Product;


class ProductSearch extends Product
{
    public function rules()
    {
        return [
            [['id', 'category_id', 'producer_id', 'price'], 'integer'],
            [['name', 'text', 'short_text', 'available', 'code'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Product::find()->with('prices');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => new \yii\data\Sort([
                'attributes' => [
                    'name',
                    'id',
                    'available',
                ],
            ])
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'available' => $this->available,
            'producer_id' => $this->producer_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'short_text', $this->short_text]);

        return $dataProvider;
    }
}
