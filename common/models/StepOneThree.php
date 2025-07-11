<?php

namespace common\models;

use common\models\AuthAssignment;
use common\models\Message;
use common\models\Student;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\httpclient\Client;

/**
 * Signup form
 */
class StepOneThree extends Model
{
    public $last_name;
    public $first_name;
    public $middle_name;

    public $birthday;
    public $passport_serial;
    public $passport_number;
    public $passport_pin;

    public function rules()
    {
        return [
            [['last_name', 'first_name', 'birthday', 'passport_serial', 'passport_number','passport_pin'], 'required'],
            [['passport_pin'], 'string', 'min' => 14, 'max' => 14, 'message' => 'Pasport pin 14 xonali bo\'lishi kerak'],
            [['passport_pin'], 'match', 'pattern' => '/^\d{14}$/', 'message' => 'Pasport pin faqat 14 ta raqamdan iborat bo‘lishi kerak'],

            [['last_name', 'first_name', 'middle_name'], 'string' , 'max' => 100],
            [['passport_serial'], 'string', 'min' => 2, 'max' => 2, 'message' => 'Pasport seria 2 xonali bo\'lishi kerak'],
            ['passport_serial', 'match', 'pattern' => '/^[^\d]*$/', 'message' => 'Pasport seriasi faqat raqamlardan iborat bo\'lmasligi kerak'],
            [['birthday'], 'safe'],
            [['passport_number'], 'string', 'min' => 7, 'max' => 7, 'message' => 'Pasport raqam 7 xonali bo\'lishi kerak'],
            ['passport_number', 'match', 'pattern' => '/^\d{7}$/', 'message' => 'Pasport raqam faqat raqamlardan iborat bo\'lishi kerak'],
        ];
    }

    function simple_errors($errors) {
        $result = [];
        foreach ($errors as $lev1) {
            foreach ($lev1 as $key => $error) {
                $result[] = $error;
            }
        }
        return array_unique($result);
    }

    public function ikStep($user, $student)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $pinfl = $student->passport_pin;

        if (!$this->validate()) {
            $errors[] = $this->simple_errors($this->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        $student->last_name = mb_strtoupper($this->last_name, 'UTF-8');
        $student->first_name = mb_strtoupper($this->first_name, 'UTF-8');
        $student->middle_name = mb_strtoupper($this->middle_name, 'UTF-8');
        $student->birthday = date("Y-m-d", strtotime($this->birthday));;
        $student->passport_serial = $this->passport_serial;
        $student->passport_number = $this->passport_number;
        $student->gender = 1;

        if ($pinfl != $this->passport_pin) {
            self::deleteNull($student->id);

            $student->passport_pin = $this->passport_pin;

            $query = Student::find()
                ->joinWith('user')
                ->where(['passport_pin' => $student->passport_pin])
                ->andWhere(['user.status' => [9, 10]])
                ->one();

            if ($query) {
                $queryUser = $query->user;
                if ($queryUser->id != $user->id) {
                    $errors[] = ['Bu pasport ma\'lumot avval ro\'yhatdan o\'tgan. Tel:' . $queryUser->username];
                    $transaction->rollBack();
                    return ['is_ok' => false, 'errors' => $errors];
                }
            }

            $amo = CrmPush::processType(3, $student, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false, 'errors' => $amo['errors']];
            }
        }

        $new = new CrmPush();
        $new->student_id = $student->id;
        $new->type = 101;
        $new->lead_id = $user->lead_id;
        $new->data = json_encode([
            CrmPush::FAMILYA => $student->last_name,
            CrmPush::ISM => $student->first_name,
            CrmPush::OTASI => $student->middle_name,
            CrmPush::SERIYA => $student->passport_serial,
            CrmPush::NOMER => $student->passport_number,
            CrmPush::BIRTHDAY => $student->birthday,
        ], JSON_UNESCAPED_UNICODE);
        $new->save(false);

        $student->update(false);
        $user->step = 2;
        $user->update(false);

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }

        $transaction->rollBack();
        return ['is_ok' => false, 'errors' => $errors];
    }

    public static function deleteNull($studentId)
    {
        try {
            Student::updateAll([
                'edu_type_id' => null,
                'edu_form_id' => null,
                'direction_id' => null,
                'edu_direction_id' => null,
                'lang_id' => null,
                'direction_course_id' => null,
                'course_id' => null,
                'edu_name' => null,
                'edu_direction' => null,
                'exam_type' => 0,
            ], ['id' => $studentId]);

            foreach (['common\models\Exam', 'common\models\ExamSubject','common\models\StudentDtm', 'common\models\StudentPerevot', 'common\models\StudentMaster', 'common\models\StudentOferta'] as $table) {
                if (class_exists($table)) {
                    call_user_func([$table, 'updateAll'], ['is_deleted' => 1], ['student_id' => $studentId, 'is_deleted' => 0]);
                }
            }
        } catch (\Exception $e) {
            Yii::error("deleteNull error: " . $e->getMessage());
        }
    }

}
