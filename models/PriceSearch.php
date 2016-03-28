<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Price;
use yii\helpers\ArrayHelper;

/**
 * PriceSearch represents the model behind the search form about `app\models\Price`.
 */
class PriceSearch extends Price
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'product', 'supplier', 'quantity'], 'integer'],
            [['started_at', 'expire_at'], 'date'],
            [['value'], 'number'],
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
        $query = Price::find()->where([
            'product' => ArrayHelper::getColumn(Product::getActiveAll(), 'id'),
        ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!isset($params['PriceSearch']['status'])) {
            $params['PriceSearch']['status'] = Price::STATUS_ACTIVE;
        }
        //var_dump($params);
        //echo PHP_EOL;
        $this->load($params);

        //var_dump($this);
        //die();

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product' => $this->product,
            'supplier' => $this->supplier,
            'quantity' => $this->quantity,
            'value' => $this->value,
        ]);

        if (!empty($this->started_at)) {
            $query->andWhere(['>=', 'started_at', Yii::$app->formatter->asTimestamp($this->started_at)]);
        }

        if (!empty($this->expire_at)) {
            $query->andWhere(['<=', 'expire_at', Yii::$app->formatter->asTimestamp($this->expire_at)]);
        }

        //var_dump($query->createCommand()->rawSql);
        //die();
        return $dataProvider;
    }
}
