<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "exam_student_questions_history".
 *
 * @property int $id
 * @property int $exam_student_question_id
 * @property int $is_correct
 * @property int $option_id
 * @property int $question_id
 * @property int|null $option_id
 * @property int|null $is_correct
 * @property string $option
 * @property int|null $subject_id
 * @property int|null $order
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Exam $exam
 * @property ExamSubject $examSubject
 * @property Options $option0
 * @property Questions $question
 * @property Subjects $subject
 * @property User $user
 */
class ExamStudentQuestionsHistory extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam_student_question_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['exam_student_question_id', 'is_correct', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['data'], 'safe'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'exam_student_question_id' => Yii::t('app', 'Exam Student Question ID'),
            'is_correct' => Yii::t('app', 'Is Correct'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }


    public function afterFind()
    {
        parent::afterFind();
        if (is_string($this->data)) {
            $this->data = json_decode($this->data, true);
        }
    }


    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_by = Yii::$app->user->identity->id;
        } else {
            $this->updated_by = Yii::$app->user->identity->id;
        }
        return parent::beforeSave($insert);
    }
}
