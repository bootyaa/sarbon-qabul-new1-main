<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "crm_push".
 *
 * @property int $id
 * @property int|null $student_id
 * @property int|null $pipeline_id
 * @property int|null $type
 * @property int|null $push_time
 * @property int|null $lead_id
 * @property int|null $lead_status
 * @property string|null $data
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 */
class CrmPush extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    const TEL = 1092891;
    const FILIAL = 1092893;
    const FAMILYA = 1092853;
    const ISM = 1092895;
    const OTASI = 1092851;
    const SERIYA = 1092849;
    const NOMER = 1092897;
    const BIRTHDAY = 1092899;
    const QABUL_TURI = 1092901;
    const TALIM_SHAKLI = 1092903;
    const TALIM_TILI = 1092905;
    const TALIM_KODI = 1092907;
    const TALIM_YONALISH = 1092909;
    const BOSQICH = 1092911;
    const EXAM_TYPE = 1092885;
    const EXAM_DATE = 1092913;
    const EXAM_BALL = 1092915;
    const IMTIXON_XOLATI = 1092917;
    const OFERTA_XOLATI = 1092919;
    const TRANSKRIPT_XOLATI = 1092921;
    const DTM_XOLATI = 1092923;
    const MASTER_XOLATI = 1092925;
    const DOMEN = 1093401;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'crm_push';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'pipeline_id', 'type', 'push_time', 'lead_id', 'lead_status', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['data'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'type' => Yii::t('app', 'Type'),
            'push_time' => Yii::t('app', 'Push Time'),
            'lead_id' => Yii::t('app', 'Lead ID'),
            'lead_status' => Yii::t('app', 'Lead Status'),
            'data' => Yii::t('app', 'Data'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }


    public function getStudent()
    {
        return $this->hasOne(Student::class, ['id' => 'student_id']);
    }

    public static function processType($id , $student, $user)
    {
        $errors = [];
        switch ($id) {
            case 1:
                $pipelineId = 8625302;
                $statusId = 69954510;
                // Ro'yhatdan o'tishda sms parol kiritmaganlar
                self::notEnteredSmsPassword($pipelineId, $statusId, $student, $user , $id);
                break;
            case 2:
                $pipelineId = 8625302;
                $statusId = 70894542;
                // Pasport ma'lumotini kiritmaganlar
                self::notEnteredPassport($pipelineId, $statusId, $student, $user , $id);
                break;
            case 3:
                $pipelineId = 8625302;
                $statusId = 70928582;
                // Qabul turini kiritmaganlar
                self::notEnteredAdmissionType($pipelineId, $statusId, $student, $user , $id);
                break;
            case 4:
                $pipelineId = 8625302;
                $statusId = 76468638;
                // Yo'nalish tanlamaganlar
                self::notSelectedDirection($pipelineId, $statusId, $student, $user , $id);
                break;
            case 5:
                $pipelineId = 8625302;
                $statusId = 76468642;
                // Tasdiqlamaganlar
                self::notConfirmed($pipelineId, $statusId, $student, $user , $id);
                break;
            case 6:
                $pipelineId = 8625302;
                $statusId = 76468646;
                // Tasdiqlandi
                self::notContract($pipelineId, $statusId, $student, $user , $id);
                break;
            case 7:
                $pipelineId = 8625302;
                $statusId = 76560838;
                // Shartnoma bekor qilindi
                self::receivedContract($pipelineId, $statusId, $student, $user , $id);
                break;
            case 8:
                $pipelineId = 8625302;
                $statusId = 77821646;
                // Shartnoma tasdiqlandi
                self::confirmContract($pipelineId, $statusId, $student, $user , $id);
                break;
            case 9:
                $pipelineId = 8625302;
                $statusId = 142;
                // Shartnoma Yuklab oldi
                self::loadContract($pipelineId, $statusId, $student, $user , $id);
                break;
            case 10:
                $pipelineId = 8625302;
                $statusId = 77821646;
                // To'lov qildi
                self::confirmContract($pipelineId, $statusId, $student, $user , $id);
                break;
            case 11:
                $pipelineId = 8625302;
                $statusId = 77821646;
                // Yopildi
                self::confirmContract($pipelineId, $statusId, $student, $user , $id);
                break;
            case 12:
                $pipelineId = 8625302;
                $statusId = 143;
                // Arxivlanganlar
                self::archived($pipelineId, $statusId, $student, $user , $id);
                break;
            default:
                $errors[] = ['Type noto\'g\'ri yuborilgan'];
                break;
        }

        if (count($errors) == 0) {
            return ['is_ok' => true];
        }
        return ['is_ok' => false, 'errors' => $errors];
    }

    public static function notEnteredSmsPassword($pipelineId, $statusId, $student, $user, $id)
    {
        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->pipeline_id = $pipelineId;
        $new->type = $id;
        $new->lead_status = $statusId;
        $new->lead_id = $user->lead_id;
        $new->data = json_encode([
            self::TEL => (string)preg_replace('/[^\d+]/', '', $student->username),
            self::FILIAL => $student->branch->name_uz,
            self::DOMEN => $user->cons->domen ?? '-',
        ], JSON_UNESCAPED_UNICODE);
        $new->save(false);
        return ['is_ok' => true];
    }

    public static function notEnteredPassport($pipelineId, $statusId, $student, $user, $id)
    {
        // Pasport ma'lumotini kiritmaganlar uchun bajariladigan amallar
        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->pipeline_id = $pipelineId;
        $new->type = $id;
        $new->lead_status = $statusId;
        $new->lead_id = $user->lead_id;
        $new->save(false);
        return true;
    }

    public static function notEnteredAdmissionType($pipelineId, $statusId, $student, $user, $id)
    {
        // Qabul turini kiritmaganlar
        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->pipeline_id = $pipelineId;
        $new->type = $id;
        $new->lead_status = $statusId;
        $new->lead_id = $user->lead_id;
        $new->data = json_encode([
            self::FAMILYA => $student->last_name ?? "",
            self::ISM => $student->first_name ?? "",
            self::OTASI => $student->middle_name ?? "",
            self::SERIYA => $student->passport_serial ?? "",
            self::NOMER => $student->passport_number ?? "",
            self::BIRTHDAY => $student->birthday ?? "",
            self::QABUL_TURI => (string)"",
            self::TALIM_SHAKLI => (string)"",
            self::TALIM_TILI => (string)"",
            self::TALIM_KODI => (string)"",
            self::TALIM_YONALISH => (string)"",
            self::BOSQICH => (string)"",
            self::EXAM_TYPE => (string)"",
            self::EXAM_DATE => (string)"",
            self::IMTIXON_XOLATI => (string)"",
            self::OFERTA_XOLATI => (string)"",
            self::TRANSKRIPT_XOLATI => (string)"",
            self::DTM_XOLATI => (string)"",
            self::MASTER_XOLATI => (string)"",
            self::EXAM_BALL => (string)"",
        ], JSON_UNESCAPED_UNICODE);
        $new->save(false);
        return true;
    }

    public static function notSelectedDirection($pipelineId, $statusId, $student, $user, $id)
    {
        // Yo'nalish tanlamaganlar uchun bajariladigan amallar
        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->pipeline_id = $pipelineId;
        $new->type = $id;
        $new->lead_status = $statusId;
        $new->lead_id = $user->lead_id;
        $new->data = json_encode([
            self::QABUL_TURI => $student->eduType->name_uz ?? "",
            self::TALIM_SHAKLI => (string)"",
            self::TALIM_TILI => (string)"",
            self::TALIM_KODI => (string)"",
            self::TALIM_YONALISH => (string)"",
            self::BOSQICH => (string)"",
            self::EXAM_TYPE => (string)"",
            self::EXAM_DATE => (string)"",
            self::IMTIXON_XOLATI => (string)"",
            self::OFERTA_XOLATI => (string)"",
            self::TRANSKRIPT_XOLATI => (string)"",
            self::DTM_XOLATI => (string)"",
            self::MASTER_XOLATI => (string)"",
            self::EXAM_BALL => (string)"",
        ], JSON_UNESCAPED_UNICODE);
        $new->save(false);
        return true;
    }

    public static function notConfirmed($pipelineId, $statusId, $student, $user, $id)
    {
        // Tasdiqlamaganlar uchun bajariladigan amallar
        $course = '1 - bosqich';
        if ($student->edu_type_id == 2) {
            $queryCourse = Course::findOne($student->course_id);
            if ($queryCourse) {
                $course = $queryCourse->name_uz;
            } else {
                $course = '-----';
            }
        }
        $examDate = null;
        if ($student->edu_type_id == 1 && in_array($student->exam_type, [0, 1])) {
            $examType = Status::getExamStatus($student->exam_type);
            if ($student->exam_type == 1) {
                $examDate = $student->examDate;
                if ($examDate) {
                    $examDate = $examDate->date;
                } else {
                    $examDate = ExamDate::findOne([
                        'status' => 1,
                        'branch_id' => $student->branch_id,
                        'is_deleted' => 0
                    ]);
                    if ($examDate) {
                        $examDate = $examDate->date;
                    } else {
                        $examDate = null;
                    }
                }
            }
        } else {
            $examType = '----';
        }
        $direction = $student->eduDirection->direction;
        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->pipeline_id = $pipelineId;
        $new->type = $id;
        $new->lead_status = $statusId;
        $new->lead_id = $user->lead_id;
        $new->data = json_encode([
            self::TALIM_SHAKLI => (string)$student->eduForm->name_uz ?? "",
            self::TALIM_TILI => (string)$student->lang->name_uz ?? "",
            self::TALIM_KODI => (string)$direction->code ?? "",
            self::TALIM_YONALISH => (string)$direction->name_uz ?? "",
            self::BOSQICH => (string)$course ?? "",
            self::EXAM_TYPE => (string)$examType ?? "",
            self::EXAM_DATE => (string)$examDate ?? "",
            self::IMTIXON_XOLATI => (string)"",
            self::OFERTA_XOLATI => (string)"",
            self::TRANSKRIPT_XOLATI => (string)"",
            self::DTM_XOLATI => (string)"",
            self::MASTER_XOLATI => (string)"",
            self::EXAM_BALL => (string)"",
        ], JSON_UNESCAPED_UNICODE);
        $new->save(false);
        return true;
    }

    public static function notContract($pipelineId, $statusId, $student, $user, $id)
    {
        // Shartnoma chiqmaganlar va shartnoma olmaganlar
        $errors = [];
        $ofertaStatus = null;
        $examStatus = null;
        $perevotStatus = null;
        $dtmStatus = null;
        $masterStatus = null;
        $ball = null;

        $ofertaCheck = true;
        $check = true;

        $eduDreiction = $student->eduDirection;
        if ($eduDreiction->is_oferta == 1) {
            $oferta = StudentOferta::findOne([
                'edu_direction_id' => $eduDreiction->id,
                'student_id' => $student->id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            if ($oferta) {
                $ofertaStatus = Status::fileStatus($oferta->file_status);
                if ($oferta->file_status != 2) {
                    $ofertaCheck = false;
                }
            } else {
                $errors[] = ['Oferta topilmadi. Biz bilan bog\'laning'];
            }
        }
        if ($student->edu_type_id == 1) {
            $exam = Exam::findOne([
                'edu_direction_id' => $eduDreiction->id,
                'student_id' => $student->id,
                'is_deleted' => 0
            ]);
            if ($exam) {
                $examStatus = Status::getExamTestStatus($exam->status);
                if ($exam->status < 3) {
                    $check = false;
                    if ($student->exam_type == 0) {
                        $statusId = 76560822;
                    } elseif ($student->exam_type == 1) {
                        $statusId = 76560826;
                    }
                } elseif ($exam->status == 4) {
                    $statusId = 76560838;
                    $check = false;
                }
                $ball = $exam->ball;
            } else {
                $errors[] = ['Imtixon topilmadi. Biz bilan bog\'laning'];
            }
        } elseif ($student->edu_type_id == 2) {
            $perevot = StudentPerevot::findOne([
                'edu_direction_id' => $eduDreiction->id,
                'student_id' => $student->id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            if ($perevot) {
                $perevotStatus = Status::fileStatus($perevot->file_status);
                if ($perevot->file_status != 2) {
                    if ($perevot->file_status == 3) {
                        $statusId = 76560838;
                    }
                    $check = false;
                }
            } else {
                $errors[] = ['Transkript topilmadi. Biz bilan bog\'laning'];
            }
        } elseif ($student->edu_type_id == 3) {
            $dtm = StudentDtm::findOne([
                'edu_direction_id' => $eduDreiction->id,
                'student_id' => $student->id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            if ($dtm) {
                $dtmStatus = Status::fileStatus($dtm->file_status);
                if ($dtm->file_status != 2) {
                    if ($dtm->file_status == 3) {
                        $statusId = 76560838;
                    }
                    $check = false;
                }
            } else {
                $errors[] = ['DTM fayl topilmadi. Biz bilan bog\'laning'];
            }
        } elseif ($student->edu_type_id == 4) {
            $master = StudentMaster::findOne([
                'edu_direction_id' => $eduDreiction->id,
                'student_id' => $student->id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            if ($master) {
                $masterStatus = Status::fileStatus($master->file_status);
                if ($master->file_status != 2) {
                    if ($master->file_status == 3) {
                        $statusId = 76560838;
                    }
                    $check = false;
                }
            } else {
                $errors[] = ['Magistr fayl topilmadi. Biz bilan bog\'laning'];
            }
        } else {
            $errors[] = ['Qabul turi tanlanmagan.'];
        }
        if (count($errors) != 0) {
            return ['is_ok' => false, 'errors' => $errors];
        }

        if ($check && $ofertaCheck) {
            $statusId = 77821646;
        }

        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->pipeline_id = $pipelineId;
        $new->type = $id;
        $new->lead_status = $statusId;
        $new->lead_id = $user->lead_id;
        $new->data = json_encode([
            self::IMTIXON_XOLATI => (string)$examStatus ?? "",
            self::OFERTA_XOLATI => (string)$ofertaStatus ?? "",
            self::TRANSKRIPT_XOLATI => (string)$perevotStatus ?? "",
            self::DTM_XOLATI => (string)$dtmStatus ?? "",
            self::MASTER_XOLATI => (string)$masterStatus ?? "",
            self::EXAM_BALL => (string)$ball ?? "",
        ], JSON_UNESCAPED_UNICODE);
        $new->save(false);
        return ['is_ok' => true];
    }

    public static function receivedContract($pipelineId, $statusId, $student, $user, $id)
    {
        // Shartnoma olganlar uchun bajariladigan amallar
        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->pipeline_id = $pipelineId;
        $new->type = $id;
        $new->lead_status = $statusId;
        $new->lead_id = $user->lead_id;
        $new->save(false);
        return true;
    }

    public static function loadContract($pipelineId, $statusId, $student, $user, $id)
    {
        // Shartnoma olganlar uchun bajariladigan amallar
        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->pipeline_id = $pipelineId;
        $new->type = $id;
        $new->lead_status = $statusId;
        $new->lead_id = $user->lead_id;
        $new->save(false);
        return true;
    }

    public static function confirmContract($pipelineId, $statusId, $student, $user, $id)
    {
        // To'lov qilganlar
        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->pipeline_id = $pipelineId;
        $new->type = $id;
        $new->lead_status = $statusId;
        $new->lead_id = $user->lead_id;
        $new->save(false);
        return true;
    }

    public static function archived($pipelineId, $statusId, $student, $user, $id)
    {
        // Arxivlangan
        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->pipeline_id = $pipelineId;
        $new->type = $id;
        $new->lead_status = $statusId;
        $new->lead_id = $user->lead_id;
        $new->save(false);
        return true;
    }
}
