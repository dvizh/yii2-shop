<?php
namespace dvizh\shop\models\modification;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dvizh\shop\models\Modification;

class ModificationSearch extends Modification
{
    public function rules()
    {
        return [
            [['id', 'product_id', 'sort'], 'integer'],
            [['name', 'available'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Modification::find()->orderBy('sort DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'available' => $this->available,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'sort', $this->sort]);
        
        return $dataProvider;
    }
}
