<?php

use common\models\{Student, Status, Course, Exam};
use yii\helpers\Url;
use common\models\StudentPerevot;
use common\models\StudentDtm;
use common\models\StudentMaster;

/** @var $student */

$lang = Yii::$app->language;
$this->title = Yii::t("app", "a40");
$eduDirection = $student->eduDirection;
$direction = $eduDirection->direction;
$t = false;
$online = true;
if ($student->edu_type_id == 1) {
    $exam = Exam::findOne([
        'student_id' => $student->id,
        'edu_direction_id' => $eduDirection->id,
        'is_deleted' => 0
    ]);
    if ($exam->status == 3) {
        $t = true;
        if ($student->exam_type == 1) {
            $online = false;
        }
    }
} elseif ($student->edu_type_id == 2) {
    $exam = StudentPerevot::findOne([
        'student_id' => $student->id,
        'edu_direction_id' => $eduDirection->id,
        'status' => 1,
        'is_deleted' => 0
    ]);
    if ($exam->file_status == 2) {
        $t = true;
    }
    $courseId = $student->course_id + 1;
    $course = Course::findOne(['id' => $courseId]);
} elseif ($student->edu_type_id == 3) {
    $exam = StudentDtm::findOne([
        'student_id' => $student->id,
        'edu_direction_id' => $eduDirection->id,
        'status' => 1,
        'is_deleted' => 0
    ]);
    if ($exam->file_status == 2) {
        $t = true;
    }
}
?>

<div class="ika_page_box">
    <div class="ika_page_box_item">
        <div class="ikpage">
            <div class="htitle">
                <h6><?= Yii::t("app", "a40") ?></h6>
                <span></span>
            </div>

            <?php if ($t && $online) : ?>
                <?= $this->render('_contract'); ?>
            <?php endif; ?>

            <div class="ika_user_page">
                <div class="row">
                    <?php
                    $userDetails = [
                        Yii::t("app", "a192") => $student->user_id,
                        Yii::t("app", "a193") => $student->fullName,
                        Yii::t("app", "a70") => $student->passport_serial . " " . $student->passport_number,
                        Yii::t("app", "a194") => $student->username,
                        Yii::t("app", "a195") => $student->password,
                        Yii::t("app", "a196") => Yii::t("app", "a197")
                    ];

                    // direction null emasligini tekshiramiz
                    $directionName = $direction ? ($direction->code . " - " . ($direction['name_' . $lang] ?? '---')) : Yii::t("app", "a191");

                    $eduDetails = [
                        Yii::t("app", "a136") => $eduDirection->eduType['name_' . $lang] ?? '---',
                        Yii::t("app", "a160") => $student->branch['name_' . $lang] ?? '---',
                        Yii::t("app", "a170") => $directionName,
                        Yii::t("app", "a16") => $eduDirection->eduForm['name_' . $lang] ?? '---',
                        Yii::t("app", "a59") => $eduDirection->lang['name_' . $lang] ?? '---'
                    ];

                    if ($student->edu_type_id == 1) {
                        $eduDetails[Yii::t("app", "a64")] = Status::getExamStatus($student->exam_type);
                        if ($student->exam_type == 1 && $student->examDate) {
                            $eduDetails[Yii::t("app", "a166")] = $student->examDate->date ?? '---';
                        }
                    }

                    if ($student->edu_type_id == 2) {
                        $courseName = Course::findOne(['id' => ($student->course_id + 1)]);
                        $eduDetails[Yii::t("app", "a81")] = $courseName['name_' . $lang] ?? '----';
                        $eduDetails[Yii::t("app", "a77")] = $student->edu_name ?? '----';
                        $eduDetails[Yii::t("app", "a79")] = $student->edu_direction ?? '----';
                    }

                    function renderList($data)
                    {
                        foreach ($data as $key => $value) {
                            echo "<ul><li>{$key}:</li><li><p>{$value}</p></li></ul>";
                        }
                    }
                    ?>

                    <div class="col-lg-6 col-md-12">
                        <div class="ika_user_page_item">
                            <?php renderList($userDetails); ?>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="ika_user_page_item">
                            <?php renderList($eduDetails); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
