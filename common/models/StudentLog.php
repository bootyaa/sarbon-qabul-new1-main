<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class StudentLog extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_log';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    // public $data;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data'], 'safe'],
            [['user_data'], 'safe'],
            [['student_id'], 'integer'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'integer'],
            [['created_by', 'updated_by'], 'integer'],
            [['is_deleted'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'data' => Yii::t('app', 'Data'),

            'student_id' => Yii::t('app', 'Student ID'),
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

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_by = Yii::$app->user->identity->id ?? 0;
        } else {
            $this->updated_by = Yii::$app->user->identity->id ?? 0;
        }

        // Faqat local bo'lmagan muhitda user_data ni yozish
        if (!YII_ENV_DEV) {
            $this->user_data = json_encode(getBrowser(), JSON_UNESCAPED_UNICODE);
        }

        return parent::beforeSave($insert);
    }
}
