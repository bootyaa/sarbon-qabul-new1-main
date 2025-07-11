<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\StudentPayment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="student-payment-form">

    <div class="modal-header mt-2">
        <h1 class="modal-title" id="exampleModalLabel">To'lov</h1>
        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row mt-2">
        <div class="col-12">
            <div class="form-group">
                <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'payment_date')->widget(\kartik\date\DatePicker::classname(), [
                    'options' => ['placeholder' => Yii::t("app" , "To'lov qilgan sana")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ])->label(Yii::t("app" , "To'lov qilgan sana").' <span>*</span>'); ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'text')->textarea(['maxlength' => true, 'rows' => 4]) ?>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-2 mb-2">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'b-btn b-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
