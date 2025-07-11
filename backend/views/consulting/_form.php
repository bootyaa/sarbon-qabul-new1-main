<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Status;
use kartik\select2\Select2;
use common\models\Branch;
use common\models\ConsultingBranch;

$branchs = Branch::find()
    ->where(['status' => 1, 'is_deleted' => 0])
    ->all();
/** @var yii\web\View $this */
/** @var common\models\Consulting $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="consulting-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row justify-content-between">
        <div class="col-12 col-md-6">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class='form-group'>
                                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'tel1')
                                    ->widget(\yii\widgets\MaskedInput::class, [
                                        'mask' => '+\9\9\8 (99) 999-99-99',
                                        'options' => [
                                            'placeholder' => '+998 (__) ___-__-__',
                                        ],
                                    ]) ?>
                            </div>

                            <div class='form-group'>
                                <?= $form->field($model, 'domen')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class='form-group'>
                                <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class='form-group'>
                                <?= $form->field($model, 'status')->widget(Select2::classname(), [
                                    'data' => Status::accessStatus(),
                                    'options' => ['placeholder' => 'Status tanlang ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="form-group">
                        <div class="col-md-6 col-6">
                            <div class="view-info-right">
                                <h6>Filiallar</h6>
                            </div>
                        </div>

                        <?php foreach ($branchs as $branch) : ?>
                            <?php
                            $isBranch = ConsultingBranch::findOne([
                                'branch_id' => $branch->id,
                                'consulting_id' => $model->id,
                                'status' => 1,
                                'is_deleted' => 0
                            ]);
                            ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="subject_box">
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" class="bu-check" name="filial[<?= $branch->id ?>]" id="check_<?= $branch->id ?>" <?php if ($isBranch) { echo "checked";} ?> value="1">
                                            <label for="check_<?= $branch->id ?>" class="permission_label"><?= $branch->name_uz ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>


            <?php for ($i = 0; $i < Status::DIRECTION_TYPE; $i++) : ?>
                <div class="form-section mt-3">
                    <div class="form-section_item">
                        <div class="form-group">
                            <div class="col-md-6 col-6">
                                <div class="view-info-right mb-3">
                                    <h6>H/R: <?= Status::directionType($i) ?></h6>
                                </div>
                            </div>

                            <?php
                            $number = '';
                            $mfo = '';
                            $bankUz = '';
                            $bankRu = '';
                            $inn = '';
                            $address = '';
                            if ($model->hr != null) {
                                $hrs = json_decode($model->hr, true);
                                if (is_array($hrs)) {
                                    foreach ($hrs as $key => $hr) {
                                        if ($key == $i) {
                                            $number = $hr['number'] ?? null;
                                            $mfo = $hr['mfo'] ?? null;
                                            $bankUz = $hr['bankUz'] ?? null;
                                            $bankRu = $hr['bankRu'] ?? null;
                                            $inn = $hr['inn'] ?? null;
                                            $address = $hr['address'] ?? null;
                                            break;
                                        }
                                    }
                                }
                            }
                            ?>

                            <div class="row mt-1">
                                <div class="col-12">
                                    <div class="form-group field-consulting-mfo has-success">
                                        <label class="control-label" for="bank<?= $i ?>">Bank nomi Uz</label>
                                        <input type="text" id="bank<?= $i ?>" class="form-control" name="number[<?= $i ?>][bankUz]" maxlength="255" aria-invalid="false" value="<?= $bankUz ?>">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group field-consulting-mfo has-success">
                                        <label class="control-label" for="bank<?= $i ?>">Bank nomi Rus</label>
                                        <input type="text" id="bank<?= $i ?>" class="form-control" name="number[<?= $i ?>][bankRu]" maxlength="255" aria-invalid="false" value="<?= $bankRu ?>">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group field-consulting-mfo has-success">
                                        <label class="control-label" for="number<?= $i ?>">Hisob raqam</label>
                                        <input type="text" id="number<?= $i ?>" class="form-control" name="number[<?= $i ?>][number]" maxlength="255" aria-invalid="false" value="<?= $number ?>">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group field-consulting-mfo has-success">
                                        <label class="control-label" for="mfo<?= $i ?>">MFO</label>
                                        <input type="text" id="mfo<?= $i ?>" class="form-control" name="number[<?= $i ?>][mfo]" maxlength="255" aria-invalid="false" value="<?= $mfo ?>">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group field-consulting-mfo has-success">
                                        <label class="control-label" for="inn<?= $i ?>">INN</label>
                                        <input type="text" id="inn<?= $i ?>" class="form-control" name="number[<?= $i ?>][inn]" maxlength="255" aria-invalid="false" value="<?= $inn ?>">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group field-consulting-mfo has-success">
                                        <label class="control-label" for="address<?= $i ?>">Bank manzili</label>
                                        <input type="text" id="address<?= $i ?>" class="form-control" name="number[<?= $i ?>][address]" maxlength="255" aria-invalid="false" value="<?= $address ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endfor; ?>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-3">
        <?= Html::submitButton('Saqlash', ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
