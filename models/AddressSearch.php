<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Address;

/**
 * AddressSearch represents the model behind the search form about `app\models\Address`.
 */
class AddressSearch extends Address
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'country', 'city'], 'integer'],
            [['postcode', 'street', 'housenumber', 'building', 'office', 'comments'], 'safe'],
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
        $query = Address::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'country' => $this->country,
            'city' => $this->city,
        ]);

        $query->andFilterWhere(['like', 'postcode', $this->postcode])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'housenumber', $this->housenumber])
            ->andFilterWhere(['like', 'building', $this->building])
            ->andFilterWhere(['like', 'office', $this->office])
            ->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
