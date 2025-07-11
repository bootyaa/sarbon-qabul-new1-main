<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "direction_ball".
 *
 * @property int $id
 * @property int|null $edu_direction_id
 * @property int|null $type
 * @property float|null $start_ball
 * @property float|null $end_ball
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property EduDirection $eduDirection
 */
class DirectionBall extends \yii\db\ActiveRecord
{

    use ResourceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'direction_ball';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_ball', 'end_ball', 'status', 'type'], 'required'],
            [['is_deleted'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
            [['edu_direction_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['start_ball', 'end_ball','type'], 'number'],
            [['edu_direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduDirection::class, 'targetAttribute' => ['edu_direction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'edu_direction_id' => Yii::t('app', 'Edu Direction ID'),
            'type' => Yii::t('app', 'Kontract barobarligi'),
            'start_ball' => Yii::t('app', 'Start Ball'),
            'end_ball' => Yii::t('app', 'End Ball'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[EduDirection]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEduDirection()
    {
        return $this->hasOne(EduDirection::class, ['id' => 'edu_direction_id']);
    }

    public static function createItem($model, $post, $eduDirection) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $model->edu_direction_id = $eduDirection->id;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        // Modelni saqlash
        if (!$model->save(false)) {
            $errors[] = ['Model saqlashda xatolik yuz berdi.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }


        if (empty($errors)) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

}
