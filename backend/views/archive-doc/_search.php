<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ArchiveDocSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="archive-doc-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'student_full_name') ?>
    <?= $form->field($model, 'phone_number') ?>
    <?= $form->field($model, 'direction') ?>
    <?= $form->field($model, 'edu_form') ?>
    <?= $form->field($model, 'submission_date') ?>

    <div class="form-group mt-2">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
