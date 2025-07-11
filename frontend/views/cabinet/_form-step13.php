<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use common\models\Student;

/** @var $model */
/** @var Student $student */
/** @var $id */

$model->last_name = $student->last_name;
$model->first_name = $student->first_name;
$model->middle_name = $student->middle_name;
$model->passport_serial = $student->passport_serial;
$model->passport_number = $student->passport_number;
$model->birthday = $student->birthday;
$model->passport_pin = $student->passport_pin;
?>


<div class="step_one_box">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'top40'],
        'fieldConfig' => [
            'template' => '{label}{input}{error}',
        ]
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'last_name')->textInput(['placeholder' => Yii::t("app" , "a66")])->label(Yii::t("app" , "a66").' <span>*</span>') ?>
    </div>

    <div class="form-group mt-4">
        <?= $form->field($model, 'first_name')->textInput(['placeholder' => Yii::t("app" , "a67")])->label(Yii::t("app" , "a67").' <span>*</span>') ?>
    </div>

    <div class="form-group mt-4">
        <?= $form->field($model, 'middle_name')->textInput(['placeholder' => Yii::t("app" , "a68")])->label(Yii::t("app" , "a68")) ?>
    </div>

    <div class="form-group mt-4">
        <?= $form->field($model, 'passport_serial')->textInput([
            'maxlength' => true,
            'placeholder' => '__',
            'oninput' => "this.value = this.value.replace(/\\d/, '').toUpperCase()"
        ])->label(Yii::t("app" , "a50").' <span>*</span>') ?>
    </div>

    <div class="form-group mt-4">
        <?= $form->field($model, 'passport_number')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '9999999',
            'options' => [
                'placeholder' => '_______',
            ],
        ])->label(Yii::t("app" , "a161").' <span>*</span>') ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => Yii::t("app" , "a53")],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd-mm-yyyy'
            ]
        ])->label(Yii::t("app" , "a49").' <span>*</span>'); ?>
    </div>

    <div class="form-group mt-4">
        <?= $form->field($model, 'passport_pin')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '99999999999999',
            'options' => [
                'placeholder' => '______________',
            ],
        ])->label(Yii::t("app" , "a51").' <span>*</span>') ?>
    </div>

    <div class="step_btn_block top40">
        <?= Html::submitButton(Yii::t("app" , "a52"), ['class' => 'step_right_btn step_btn', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>





