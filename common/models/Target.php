<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "target".
 *
 * @property int $id
 * @property int|null $cons_id
 * @property int|null $type
 * @property string $name
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int $is_deleted
 *
 * @property Consulting $cons
 * @property User[] $users
 */
class Target extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'target';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cons_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['is_deleted'], 'default', 'value' => 0],
            [['cons_id', 'type', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['cons_id'], 'exist', 'skipOnError' => true, 'targetClass' => Consulting::class, 'targetAttribute' => ['cons_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cons_id' => Yii::t('app', 'Cons ID'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[Cons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCons()
    {
        return $this->hasOne(Consulting::class, ['id' => 'cons_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['target_id' => 'id']);
    }


    public function getStudentsCount()
    {
        return Student::find()
            ->alias('s')
            ->innerJoin('user u', 's.user_id = u.id')
            ->where([
                'u.user_role' => 'student',
                'u.status' => [9,10],
                's.is_deleted' => 0,
                'u.step' => 5,
                'u.target_id' => $this->id,
            ])->count();
    }

    public function getChalaStudentsCount()
    {
        return Student::find()
            ->alias('s')
            ->innerJoin('user u', 's.user_id = u.id')
            ->where([
                'u.user_role' => 'student',
                'u.status' => [9,10],
                's.is_deleted' => 0,
                'u.target_id' => $this->id,
            ])
            ->andWhere(['<', 'u.step' , 5])
            ->count();
    }

    public function getContract()
    {
        return Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->leftJoin(Exam::tableName() . ' e', 's.id = e.student_id AND e.status = 3 AND e.is_deleted = 0')
            ->leftJoin(StudentPerevot::tableName() . ' sp', 's.id = sp.student_id AND sp.file_status = 2 AND sp.is_deleted = 0')
            ->leftJoin(StudentDtm::tableName() . ' sd', 's.id = sd.student_id AND sd.file_status = 2 AND sd.is_deleted = 0')
            ->leftJoin(StudentMaster::tableName() . ' sm', 's.id = sm.student_id AND sm.file_status = 2 AND sm.is_deleted = 0')
            ->where([
                'u.step' => 5,
                'u.status' => [9, 10],
                'u.user_role' => 'student',
                's.is_deleted' => 0,
                'u.target_id' => $this->id,
            ])
            ->andWhere([
                'or',
                ['not', ['e.student_id' => null]],
                ['not', ['sp.student_id' => null]],
                ['not', ['sd.student_id' => null]],
                ['not', ['sm.student_id' => null]]
            ])->count();
    }

    public function getContractLoad()
    {
        return Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->leftJoin(Exam::tableName() . ' e', 's.id = e.student_id AND e.status = 3 AND e.is_deleted = 0')
            ->leftJoin(StudentPerevot::tableName() . ' sp', 's.id = sp.student_id AND sp.file_status = 2 AND sp.is_deleted = 0')
            ->leftJoin(StudentDtm::tableName() . ' sd', 's.id = sd.student_id AND sd.file_status = 2 AND sd.is_deleted = 0')
            ->leftJoin(StudentMaster::tableName() . ' sm', 's.id = sm.student_id AND sm.file_status = 2 AND sm.is_deleted = 0')
            ->where([
                'u.step' => 5,
                'u.status' => [9, 10],
                'u.user_role' => 'student',
                's.is_deleted' => 0,
                'u.target_id' => $this->id,
                's.is_down' => 1,
            ])
            ->andWhere([
                'or',
                ['not', ['e.student_id' => null]],
                ['not', ['sp.student_id' => null]],
                ['not', ['sd.student_id' => null]],
                ['not', ['sm.student_id' => null]]
            ])->count();
    }

}
