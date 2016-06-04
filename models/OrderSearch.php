<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order
{
    public $request;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'expire_at', 'quotation', 'request', 'seller'], 'integer'],
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
        $query = Order::find();
        $query->joinWith('quotation');

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

        $dataProvider->sort->attributes['request'] = [
            'asc' => ['quotation.request' => SORT_ASC],
            'desc' => ['quotation.request' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'order.id' => $this->id,
            'order.status' => $this->status,
            'order.created_at' => $this->created_at,
            'order.updated_at' => $this->updated_at,
            'order.expire_at' => $this->expire_at,
            'order.quotation' => $this->quotation,
            'order.seller' => $this->seller,
        ]);

        if (isset($this->request)) {
            $query->andFilterWhere(['quotation.request' => $this->request]);
        }

        return $dataProvider;
    }
}
