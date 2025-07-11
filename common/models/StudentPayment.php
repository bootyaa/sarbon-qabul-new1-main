<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student_payment".
 *
 * @property int $id
 * @property int|null $student_id
 * @property float|null $price
 * @property string|null $payment_date
 * @property string|null $text
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Student $student
 */
class StudentPayment extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_date', 'price'], 'required'],
            [['student_id', 'payment_date', 'text', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['is_deleted'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
            [['student_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['price'], 'number'],
            [['payment_date', 'text'], 'string', 'max' => 255],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'id']],
            [['price'], 'validatePrice'],
        ];
    }

    public function validatePrice($attribute, $params)
    {
        if ($this->$attribute <= 0) {
            $this->addError($attribute, 'Narx 0 dan katta bo‘lishi kerak.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'price' => Yii::t('app', 'Summa'),
            'payment_date' => Yii::t('app', 'To\'lov sanasi'),
            'text' => Yii::t('app', 'To\'lov haqida qisqacha'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[Student]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::class, ['id' => 'student_id']);
    }

    public static function createItem($student, $model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $model->student_id = $student->id;
        $model->status = 1;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $model->save(false);

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false , 'errors' => $errors];
    }

}
