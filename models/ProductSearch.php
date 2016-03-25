<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * ProductSearch represents the model behind the search form about `app\models\Product`.
 */
class ProductSearch extends Product
{
    public $material;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'material', 'dia', 'thread', 'package'], 'integer'],
            [['material', 'code', 'name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find();

        // add conditions that should always apply here

        $query->joinWith(['material']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['material'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['material.name' => SORT_ASC],
            'desc' => ['material.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'dia' => $this->dia,
            'thread' => $this->thread,
            'package' => $this->package,
        ]);

        $query->andFilterWhere(['like', 'product.name', $this->name]);
        $query->andFilterWhere(['like', 'product.code', $this->code]);

        $query->andFilterWhere(['like', 'material.name', $this->material]);

        return $dataProvider;
    }
}
