<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var common\models\DirectionBall $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="direction-ball-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="form-group">
                <?= $form->field($model, 'type')->textInput() ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="form-group">
                <?= $form->field($model, 'start_ball')->textInput() ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="form-group">
                <?= $form->field($model, 'end_ball')->textInput() ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="form-group">
                <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => Status::accessStatus(),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-2">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
