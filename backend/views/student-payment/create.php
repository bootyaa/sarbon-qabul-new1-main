<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StudentPayment $model */

$this->title = Yii::t('app', 'Create Student Payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-payment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
