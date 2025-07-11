<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DirectionSubject;

/**
 * ExamStudentQuestionsSearch represents the model behind the search form of `common\models\ExamStudentQuestions`.
 */
class ExamStudentQuestionsSearch extends ExamStudentQuestions
{

    public $question_text;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'is_correct', 'exam_id', 'exam_subject_id', 'question_id', 'option_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            ['question_text', 'string'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params, $exam)
    {
        $query = ExamStudentQuestions::find()
            ->with(['question', 'user', 'createdBy', 'updatedBy'])
            ->where(['exam_id' => $exam->id, 'status' => 1, 'is_deleted' => 0])
            ->orderBy('order asc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'is_correct' => $this->is_correct,
            'status' => $this->status,
            'is_deleted' => $this->is_deleted,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);


        return $dataProvider;
    }

    public function searchNew($params, $student)
    {
        $this->load($params);

        $query = ExamStudentQuestions::find()
            ->with(['question', 'user', 'createdBy', 'updatedBy'])
            ->joinWith(['question q'])
            ->where([
                'user_id' => $student->user_id,
                // 'status' => 1,
                // 'is_deleted' => 0
            ])
            ->orderBy('order asc');

        $query->andFilterWhere(['like', 'q.text', $this->question_text]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 40,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'is_correct' => $this->is_correct,
            // 'status' => $this->status,
            // 'is_deleted' => $this->is_deleted,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);


        return $dataProvider;
    }
}
