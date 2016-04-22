<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\models\Product;

/**
 * ProductSearch represents the model behind the search form about `app\models\Product`.
 */
class ProductSearch extends Product
{
    public $material;
    public $complex_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'material', 'dia', 'thread', 'package'], 'integer'],
            [['material', 'code', 'name', 'complex_name'], 'safe'],
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
    public function search($params, $returnType = 'Active')
    {
        $query = Product::find();

        // add conditions that should always apply here

        $query->joinWith(['material']);

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
//        $query->andFilterWhere(['like', 'material.name', $this->stock]);
//        $query->andFilterWhere(['like', 'material.name', $this->price]);

        if (isset($this->complex_name)) {
            $complexNameParts = explode(' ', trim($this->complex_name));
            foreach ($complexNameParts as $complexNamePart) {
                $query->orFilterWhere(['LIKE', 'product.name', $complexNamePart]);
                $query->orFilterWhere(['LIKE', 'product.code', $complexNamePart]);
                $query->orFilterWhere(['LIKE', 'material.name', $complexNameParts]);
                $query->orFilterWhere(['LIKE', 'dia', $complexNameParts]);
                $query->orFilterWhere(['LIKE', 'thread', $complexNameParts]);    
            }
        }
        
        //var_dump($query->createCommand()->rawSql);
        //die();

        switch ($returnType) {
            case 'Active':
                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                ]); 
                break;
            case 'Array':
                $dataProvider = new ArrayDataProvider([
                    'allModels' => $query->all(),
                ]);
                break;
            default:
                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                ]); 
        }

        $dataProvider->sort->attributes['material'] = [
            'asc' => ['material.name' => SORT_ASC],
            'desc' => ['material.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['stock'] = [
            'asc' => ['material.name' => SORT_ASC],
            'desc' => ['material.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['price'] = [
            'asc' => ['material.name' => SORT_ASC],
            'desc' => ['material.name' => SORT_DESC],
        ];

        return $dataProvider;
    }
}
