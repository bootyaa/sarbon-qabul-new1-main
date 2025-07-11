<?php

use yii\helpers\Url;
use common\models\Student;
use common\models\StudentPerevot;
use yii\helpers\Html;
use common\models\StudentOferta;
use common\models\Direction;
use common\models\Exam;
use common\models\Course;
use common\models\Status;
use common\models\ExamSubject;

/** @var Student $student */
/** @var Direction $direction */

$this->title = Yii::t("app", "a120");
$lang = Yii::$app->language;
$direction = $student->eduDirection->direction;

$eduDirection = $student->eduDirection;

$exam = Exam::findOne([
    'student_id' => $student->id,
    'edu_direction_id' => $eduDirection->id,
    'is_deleted' => 0
]);
$documents = [];
$subjects = [];
if ($exam) {
    $subjects = ExamSubject::find()
        ->where([
            'edu_direction_id' => $eduDirection->id,
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'status' => 1,
            'is_deleted' => 0
        ])->all();
}

$is_exam = false;
if ($eduDirection->is_oferta == 1) {
    $oferta = StudentOferta::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'status' => 1,
        'is_deleted' => 0
    ]);
    if ($oferta->file_status == 2) {
        $is_exam = true;
    }
} else {
    $is_exam = true;
}
?>

<div class="ika_page_box">
    <div class="ika_page_box_item">
        <div class="ikpage">
            <div class="htitle">
                <h6><?= Yii::t("app", "a120") ?></h6>
                <span></span>
            </div>

            <?php if ($exam->status == 3) : ?>
                <?php if ($eduDirection->type == 0) : ?>
                    <?= $this->render('_contract'); ?>
                <?php else: ?>
                    <?= $this->render('_no-contract'); ?>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row top30">
                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p><?= Yii::t("app", "a170") ?></p>
                        <h6>
                            <?= $direction ? ($direction->code . " - " . ($direction['name_' . $lang] ?? '---')) : Yii::t("app", "a169") ?>
                        </h6>

                    </div>
                </div>

                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p><?= Yii::t("app", "a59") ?></p>
                        <h6><?= $eduDirection->lang['name_' . $lang] ?? "-" ?></h6>
                    </div>
                </div>

                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p><?= Yii::t("app", "a60") ?></p>
                        <h6><?= $eduDirection->eduForm['name_' . $lang] ?? "-" ?></h6>
                    </div>
                </div>

                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p><?= Yii::t("app", "a64") ?></p>
                        <h6><?= Status::getExamStatus($student->exam_type); ?></h6>
                    </div>
                </div>

                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p><?= Yii::t("app", "a171") ?></p>
                        <h6>
                            <?php
                            switch ($exam->status) {
                                case 1:
                                    echo Yii::t("app", "a172");
                                    break;
                                case 2:
                                    echo Yii::t("app", "a173");
                                    break;
                                case 3:
                                    echo Yii::t("app", "a174");
                                    break;
                                case 4:
                                    echo Yii::t("app", "a175");
                                    break;
                                default:
                                    echo "---";
                                    break;
                            }
                            ?>
                        </h6>
                    </div>
                </div>

                <?php if ($exam->status >= 3): ?>
                    <div class="col-md-4 col-12 mb-4">
                        <div class="ika_column">
                            <p><?= Yii::t("app", "a176") ?></p>
                            <h6><?= $exam->ball ?> <?= Yii::t("app", "a99") ?></h6>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-md-4 col-12 mb-4">
                    <div class="ika_column">
                        <p><?= Yii::t("app", "a177") ?></p>
                        <h6>
                            <?= Yii::t("app", "a178") ?>
                        </h6>
                    </div>
                </div>

                <?php if (!empty($subjects)) : ?>
                    <div class="col-md-12">
                        <div class="ika_column">
                            <p><?= Yii::t("app", "a95") ?></p>
                            <div class="row mt-2">
                                <?php foreach ($subjects as $subject) : ?>
                                    <?php $directionSubject = $subject->directionSubject; ?>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="ika_user_page_item">
                                            <ul>
                                                <li><?= Yii::t("app", "a179") ?></li>
                                                <li>
                                                    <p><?= $subject->subject['name_' . $lang] ?? "-" ?></p>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li><?= Yii::t("app", "a180") ?></li>
                                                <li>
                                                    <p><?= $directionSubject->count ?? "0" ?></p>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li><?= Yii::t("app", "a181") ?></li>
                                                <li>
                                                    <p><?= $directionSubject->ball ?? "0" ?> <?= Yii::t("app", "a99") ?></p>
                                                </li>
                                            </ul>
                                            <?php if ($exam->status > 3 && $exam->ball > 80) : ?>
                                                <ul>
                                                    <li><?= Yii::t("app", "a182") ?></li>
                                                    <li>
                                                        <p><?= $subject->ball ?> <?= Yii::t("app", "a99") ?></p>
                                                    </li>
                                                </ul>
                                            <?php endif; ?>
                                            <ul>
                                                <li><?= Yii::t("app", "a183") ?></li>
                                                <li>
                                                    <p>
                                                        <?php
                                                        $statuses = [
                                                            0 => "Yuklanmagan",
                                                            1 => '<a href="/frontend/web/uploads/' . $student->id . '/' . $subject->file . '">'. Yii::t("app", "a184") .' <i class="bi bi-arrow-up-right-circle"></i></a>',
                                                            2 => '<a href="/frontend/web/uploads/' . $student->id . '/' . $subject->file . '">'. Yii::t("app", "a185") .' <i class="bi bi-arrow-up-right-circle"></i></a>',
                                                            3 => '<a href="/frontend/web/uploads/' . $student->id . '/' . $subject->file . '">'. Yii::t("app", "a186") .' <i class="bi bi-arrow-up-right-circle"></i></a>',
                                                        ];
                                                        echo $statuses[$subject->file_status] ?? Yii::t("app", "a187");
                                                        ?>
                                                    </p>
                                                </li>
                                            </ul>

                                            <?php if ($subject->file_status == 0) : ?>
                                                <div class="ika_user_page_button">
                                                    <?php
                                                    $url = Url::to(['file/create-sertificate', 'id' => $subject->id]);
                                                    echo Html::a('<span>' . Yii::t("app", "a101") . '</span><i class="bi bi-arrow-up-circle"></i>', $url, [
                                                        "data-bs-toggle" => "modal",
                                                        "data-bs-target" => "#studentModalUpload",
                                                    ]);
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($exam->status < 3) : ?>
                    <?php if ($is_exam) : ?>
                        <?php if ($student->ipCheck) : ?>
                            <div class="d-flex justify-content-center top30">
                                <a href="<?= Url::to(['cabinet/test']) ?>" class="linkExam">
                                    <span>
                                        <?php if ($exam->status == 1) : ?>
                                            <?= Yii::t("app", "a130") ?>
                                        <?php elseif ($exam->status == 2) : ?>
                                            <?= Yii::t("app", "a131") ?>
                                        <?php endif; ?>
                                    </span>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="col-md-12 col-12">
                                <div class="ika_danger top30">
                                    <h6><i class="fa-solid fa-exclamation"></i> <span><?= getIpMK() ?> <?= Yii::t("app", "a188") ?></span></h6>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="col-md-12 col-12">
                            <div class="ika_danger top30">
                                <h6><i class="fa-solid fa-exclamation"></i> <span><?= Yii::t("app", "a189") ?></span></h6>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($eduDirection->is_oferta == 1) : ?>
            <div class="ikpage top30">
                <div class="htitle">
                    <h6><?= Yii::t("app", "a127") ?></h6>
                    <span></span>
                </div>
                <div class="row top30">
                    <div class="col-lg-6 col-md-12">
                        <div class="cfile_box">
                            <div class="cfile_box_head_right <?= ($oferta->file_status == 0 || $oferta->file_status == 3) ? 'danger' : (($oferta->file_status == 2) ? 'active' : '') ?>">
                                <p><?= Yii::t("app", "a" . (82 + $oferta->file_status)) ?></p>
                            </div>
                            <div class="cfile_box_head">
                                <div class="cfile_box_head_left">
                                    <h5>&nbsp; <span></span> &nbsp;&nbsp; <?= Yii::t("app", "a127") ?></h5>
                                </div>
                            </div>
                            <div class="cfile_box_content_question">
                                <p><span><i class="fa-solid fa-exclamation"></i></span>
                                    <?= Yii::t("app", "a190") ?> </p>
                            </div>
                            <?php if ($oferta->file_status == 0) : ?>
                                <div class="cfile_box_content_upload">
                                    <?= Html::a(Yii::t("app", "a128"), Url::to(['file/create-oferta', 'id' => $oferta->id]), [
                                        "data-bs-toggle" => "modal",
                                        "data-bs-target" => "#studentModalUpload"
                                    ]) ?>
                                </div>
                            <?php else: ?>
                                <div class="cfile_box_content">
                                    <div class="cfile_box_content_file">
                                        <div class="cfile_box_content_file_left">
                                            <a href="/frontend/web/uploads/<?= $oferta->student_id ?>/<?= $oferta->file ?>" target="_blank">
                                                <span><i class="fa-solid fa-file-export"></i></span> <?= Yii::t("app", "a89") ?>
                                            </a>
                                        </div>
                                        <?php if ($oferta->file_status != 2) : ?>
                                            <div class="cfile_box_content_file_right">
                                                <?= Html::a('<i class="fa-solid fa-trash"></i>', Url::to(['file/del-oferta', 'id' => $oferta->id]), [
                                                    "data-bs-toggle" => "modal",
                                                    "data-bs-target" => "#studentModalDelete"
                                                ]) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="studentModalUpload" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" id="modalUploadBody"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="studentModalDelete" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" id="modalDeleteBody"></div>
        </div>
    </div>
</div>
<?php
$js = <<<JS
$(document).ready(function() {
    $('#studentModalUpload, #studentModalDelete').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('.modal-body').load(url);
    });
});
JS;
$this->registerJs($js);
?>
