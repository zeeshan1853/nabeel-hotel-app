<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Hotel;

/**
 * HotelSearch represents the model behind the search form of `common\models\Hotel`.
 */
class HotelSearch extends Hotel {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['id', 'category_id', 'created_at'], 'integer'],
                [['city'], 'safe'],
                [['website','fb_address','phone_no','contact_email'],'safe'],
                [['name', 'update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Hotel::find();

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
            'category_id' => $this->category_id,
            'created_at' => $this->created_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'city', $this->city]);
        $query->andFilterWhere(['like', 'website', $this->website]);
        $query->andFilterWhere(['like', 'fb_address', $this->fb_address]);
        $query->andFilterWhere(['like', 'phone_no', $this->phone_no]);
        $query->andFilterWhere(['like', 'contact_email', $this->contact_email]);

        return $dataProvider;
    }

}
