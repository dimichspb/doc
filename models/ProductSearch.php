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
            [['id', 'status', 'material', 'length', 'thread', 'package'], 'integer'],
            [['dia'], 'number'],
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

        $query->joinWith(['material']);

        $this->load($params);

        if (isset($this->complex_name)) {
            $complexNameParts = explode(' ', trim($this->complex_name));
            foreach ($complexNameParts as $complexNamePart) {
                $complexNamePart = str_replace('DIN', '', $complexNamePart);
                $query->andWhere("
                    (`product`.`name` LIKE '%$complexNamePart%') OR
                    (`product`.`code` LIKE '%$complexNamePart%') OR
                    (`material`.`name` LIKE '%$complexNamePart$') OR
                    (`product`.`dia` LIKE '%$complexNamePart%') OR
                    (`product`.`thread` LIKE '%$complexNamePart%') OR
                    (CONCAT('M',`product`.`thread`) LIKE '%$complexNamePart%') OR
                    (`product`.`length` LIKE '%$complexNamePart%')
                    ");
            }
        } else {
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
        }

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
        $dataProvider->sort->attributes['image_file'] = null;
        $dataProvider->sort->attributes['drawing_file'] = null;

        return $dataProvider;
    }
}
