<?php
namespace dvizh\shop\models\price;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dvizh\shop\models\Price;

class PriceSearch extends Price
{
    public function rules()
    {
        return [
            [['id', 'item_id'], 'integer'],
            [['name', 'available'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Price::find()->orderBy('sort DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'item_id' => $this->item_id,
            'available' => $this->available,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
