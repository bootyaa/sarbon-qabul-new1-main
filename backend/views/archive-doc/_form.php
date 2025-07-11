<?php

use common\models\Exam;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentMaster;
use common\models\StudentPerevot;
use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;


/** @var yii\web\View $this */
/** @var app\models\ArchiveDoc $model */
/** @var yii\widgets\ActiveForm $form */

$query = Student::find()
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
    ])
    ->andWhere(getConsIk())
    ->andWhere([
        'or',
        ['not', ['e.student_id' => null]],
        ['not', ['sp.student_id' => null]],
        ['not', ['sd.student_id' => null]],
        ['not', ['sm.student_id' => null]]
    ])->all();
?>

<div class="archive-doc-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'student_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            $query,
            'id',
            function ($model) {
                return $model->first_name . ' ' . $model->last_name . ' ' . $model->middle_name .
                    ' | 📞 ' . $model->username .
                    ' | 🆔 ' . $model->passport_serial . $model->passport_number .
                    ' | PIN: ' . $model->passport_pin;
            }
        ),
        'options' => ['placeholder' => 'Talabani tanlang...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Talabalar ( '.count($query).' )') ?>

    <hr>
    <h4>📎 Hujjatlar</h4>
    <div class="row">
        <div class="col-md-6"><?= $form->field($model, 'application_letter')->checkbox() ?></div>
        <div class="col-md-6"><?= $form->field($model, 'passport_copy')->checkbox() ?></div>
        <div class="col-md-6"><?= $form->field($model, 'diploma_original')->checkbox() ?></div>
        <div class="col-md-6"><?= $form->field($model, 'photo_3x4')->checkbox() ?></div>
        <div class="col-md-6"><?= $form->field($model, 'contract_copy')->checkbox() ?></div>
        <div class="col-md-6"><?= $form->field($model, 'payment_receipt')->checkbox() ?></div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>