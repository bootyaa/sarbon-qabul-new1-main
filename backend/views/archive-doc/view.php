<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ArchiveDoc $model */

$this->title = $model->student_full_name;
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Arxiv'),
    'url' => ['index'],
];
\yii\web\YiiAsset::register($this);
?>
<div class="page">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumbs['item'] as $item) : ?>
                <li class='breadcrumb-item'>
                    <?= Html::a($item['label'], $item['url'], ['class' => '']) ?>
                </li>
            <?php endforeach; ?>
            <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
        </ol>
    </nav>

    <p>
        <!-- <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?> -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'student_full_name',
            'phone_number',
            'direction',
            'edu_form',
            'submission_date',
            'application_letter:boolean',
            'passport_copy:boolean',
            'diploma_original:boolean',
            'photo_3x4:boolean',
            'contract_copy:boolean',
            'payment_receipt:boolean',
        ],
    ]) ?>


    <?= Html::a('<i class="fa fa-file-pdf"></i> PDF yuklash', ['pdf', 'id' => $model->id], [
        'class' => 'btn btn-outline-danger',
        'target' => '_blank'
    ]) ?>



</div>