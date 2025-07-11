<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ArchiveDocSearch represents the model behind the search form of `app\models\ArchiveDoc`.
 */
class ArchiveDocSearch extends ArchiveDoc
{
    public function rules()
    {
        return [
            [['id', 'student_id', 'direction_id', 'edu_form_id', 'edu_direction_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['direction', 'edu_form', 'student_full_name', 'phone_number', 'submission_date'], 'safe'],
            [['application_letter', 'passport_copy', 'diploma_original', 'photo_3x4', 'contract_copy', 'payment_receipt'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ArchiveDoc::find()->where(['is_deleted' => 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'student_id' => $this->student_id,
            'direction_id' => $this->direction_id,
            'edu_form_id' => $this->edu_form_id,
            'edu_direction_id' => $this->edu_direction_id,
            'submission_date' => $this->submission_date,
            'application_letter' => $this->application_letter,
            'passport_copy' => $this->passport_copy,
            'diploma_original' => $this->diploma_original,
            'photo_3x4' => $this->photo_3x4,
            'contract_copy' => $this->contract_copy,
            'payment_receipt' => $this->payment_receipt,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'direction', $this->direction])
            ->andFilterWhere(['like', 'edu_form', $this->edu_form])
            ->andFilterWhere(['like', 'student_full_name', $this->student_full_name])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number]);

        return $dataProvider;
    }
}
