<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "archive_doc".
 *
 * @property int $id
 * @property int|null $student_id
 * @property int|null $direction_id
 * @property int|null $edu_form_id
 * @property int|null $edu_direction_id
 * @property string|null $direction
 * @property string|null $edu_form
 * @property string|null $student_full_name
 * @property string|null $phone_number
 * @property string|null $submission_date
 * @property bool $application_letter
 * @property bool $passport_copy
 * @property bool $diploma_original
 * @property bool $photo_3x4
 * @property bool $contract_copy
 * @property bool $payment_receipt
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 */
class ArchiveDoc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'archive_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id'], 'required'],
            [['student_id', 'direction_id', 'edu_form_id', 'edu_direction_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['submission_date'], 'safe'],
            [['submission_date'], 'default', 'value' => date('Y-m-d')],
            [['application_letter', 'passport_copy', 'diploma_original', 'photo_3x4', 'contract_copy', 'payment_receipt'], 'boolean'],
            [['direction', 'student_full_name'], 'string', 'max' => 255],
            [['edu_form'], 'string', 'max' => 100],
            [['phone_number'], 'string', 'max' => 50],

            [['student_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'id']],
            [['direction_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Direction::class, 'targetAttribute' => ['direction_id' => 'id']],
            [['edu_form_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => EduForm::class, 'targetAttribute' => ['edu_form_id' => 'id']],
            [['edu_direction_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => EduDirection::class, 'targetAttribute' => ['edu_direction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'student_id' => Yii::t('app', 'Talaba '),
            'direction_id' => Yii::t('app', 'Asosiy yo‘nalish ID'),
            'edu_form_id' => Yii::t('app', 'Ta\'lim shakli ID'),
            'edu_direction_id' => Yii::t('app', 'Tashkilot ID'),
            'direction' => Yii::t('app', 'Yo‘nalish nomi'),
            'edu_form' => Yii::t('app', 'Ta\'lim shakli nomi'),
            'student_full_name' => Yii::t('app', 'Talabaning to‘liq ismi'),
            'phone_number' => Yii::t('app', 'Telefon raqami'),
            'submission_date' => Yii::t('app', 'Hujjat topshirilgan sana'),

            'application_letter' => Yii::t('app', 'Rektor nomiga ariza'),
            'passport_copy' => Yii::t('app', 'Passport nusxasi'),
            'diploma_original' => Yii::t('app', 'Diplom yoki attestat (ilova) asl nusxa'),
            'photo_3x4' => Yii::t('app', '3x4 rasm'),
            'contract_copy' => Yii::t('app', 'Shartnoma nusxasi'),
            'payment_receipt' => Yii::t('app', 'To‘lov cheki'),

            'created_at' => Yii::t('app', 'Yaratilgan vaqti'),
            'updated_at' => Yii::t('app', 'O‘zgartirilgan vaqti'),
            'created_by' => Yii::t('app', 'Yaratgan foydalanuvchi'),
            'updated_by' => Yii::t('app', 'O‘zgartirgan foydalanuvchi'),
            'is_deleted' => Yii::t('app', 'O‘chirilgan (soft delete)'),
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

    public function getDirection()
    {
        return $this->hasOne(Direction::class, ['id' => 'direction_id']);
    }

    public function getEduForm()
    {
        return $this->hasOne(EduForm::class, ['id' => 'edu_form_id']);
    }

    public function getEduDirection()
    {
        return $this->hasOne(EduDirection::class, ['id' => 'edu_direction_id']);
    }
}
