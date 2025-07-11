<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DirectionBall;

/**
 * DirectionBallSearch represents the model behind the search form of `common\models\DirectionBall`.
 */
class DirectionBallSearch extends DirectionBall
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'edu_direction_id', 'type', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['start_ball', 'end_ball'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $eduDirection)
    {
        $query = DirectionBall::find()->where(['is_deleted' => 0 , 'edu_direction_id' => $eduDirection->id]);

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
            'edu_direction_id' => $this->edu_direction_id,
            'type' => $this->type,
            'start_ball' => $this->start_ball,
            'end_ball' => $this->end_ball,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_deleted' => $this->is_deleted,
        ]);

        return $dataProvider;
    }
}
