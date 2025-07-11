<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StudentPayment;

/**
 * StudentPaymentSearch represents the model behind the search form of `common\models\StudentPayment`.
 */
class StudentPaymentSearch extends StudentPayment
{
    public $first_name;
    public $last_name;
    public $middle_name;
    public $passport_serial;
    public $passport_number;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'student_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['price'], 'number'],
            [[
                'payment_date',
                'text',
                'first_name',
                'last_name',
                'middle_name',
                'passport_serial',
                'passport_number',
            ], 'safe'],
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
    public function search($params, $id)
    {
        $query = StudentPayment::find()
            ->where(['student_id' => $id, 'is_deleted' => 0])->orderBy('id desc');

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
            'student_id' => $this->student_id,
            'price' => $this->price,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'payment_date', $this->payment_date])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }

    public function searchIndex($params)
    {
        $query = StudentPayment::find()
            ->joinWith('student')
            ->where(['student_payment.is_deleted' => 0])
            ->orderBy('student_payment.id desc');

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
            'student_id' => $this->student_id,
            'price' => $this->price,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'payment_date', $this->payment_date])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'student.first_name', $this->first_name])
            ->andFilterWhere(['like', 'student.last_name', $this->last_name])
            ->andFilterWhere(['like', 'student.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'student.passport_serial', $this->passport_serial])
            ->andFilterWhere(['like', 'student.passport_number', $this->passport_number]);

        return $dataProvider;
    }
}
