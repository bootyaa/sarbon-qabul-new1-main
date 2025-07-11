<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ArchiveDoc $model */

$this->title = 'Update Document: ' . $model->student_full_name;
$this->params['breadcrumbs'][] = ['label' => 'Archive Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->student_full_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="archive-doc-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
