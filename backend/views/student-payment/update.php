<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StudentPayment $model */

$this->title = Yii::t('app', 'Update Student Payment: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="student-payment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
