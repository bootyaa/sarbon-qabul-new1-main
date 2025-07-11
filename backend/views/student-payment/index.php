<?php

use common\models\StudentPayment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\StudentPaymentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'To\'lovlar');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$totalSum = clone $dataProvider->query;
$totalSum = $totalSum->sum('price');
?>
<div class="student-payment-index">

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

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $data = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'F.I.O',
            'contentOptions' => ['date-label' => 'F.I.O'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->student->fullName ?? '---';
            },
        ],
        [
            'attribute' => 'F.I.O',
            'contentOptions' => ['date-label' => 'F.I.O'],
            'format' => 'raw',
            'value' => function($model) {
                $student = $model->student;
                return $student->passport_serial ." | ".$student->passport_number;
            },
        ],
        [
            'attribute' => 'price',
            'contentOptions' => ['date-label' => 'price'],
            'format' => 'raw',
            'value' => function($t) {
                return number_format($t->price, 0, '', ' ');
            },
        ],
        'payment_date',
        'text',
        [
            'attribute' => 'Batafsil',
            'contentOptions' => ['date-label' => 'Batafsil'],
            'format' => 'raw',
            'value' => function($model) {
                if (permission('student', 'view')) {
                    $readMore = "<a href='".Url::to(['student/view' , 'id' => $model->student_id])."' class='badge-table-div active'>Batafsil</a>";
                    return $readMore;
                }
            },
        ],
        [
            'class' => \yii\grid\ActionColumn::className(),
            'contentOptions' => ['date-label' => 'Harakatlar' , 'class' => 'd-flex justify-content-around'],
            'header'=> 'Harakatlar',
            'buttons'  => [
                'view'   => function () {
                    return false;
                },
                'update' => function ($url, $model) {
                    if (permission('student-payment', 'update')) {
                        $url = Url::to(['student-payment/update', 'id' => $model->id]);
                        return Html::a('<i class="fa-solid fa-pen-to-square"></i>', $url, [
                            'title' => 'update',
                            'class' => 'tableIcon',
                            "data-bs-toggle" => "modal",
                            "data-bs-target" => "#studentInfoDate",
                        ]);
                    }
                    return false;
                },
                'delete' => function ($url, $model) {
                    if (permission('student-payment', 'delete')) {
                        $url = Url::to(['student-payment/delete', 'id' => $model->id]);
                        return Html::a('<i class="fa fa-trash"></i>', $url, [
                            'title' => 'delete',
                            'class' => 'tableIcon',
                            'data-confirm' => Yii::t('yii', 'Ma\'lumotni o\'chirishni xoxlaysizmi?'),
                            'data-method'  => 'post',
                        ]);
                    }
                    return false;
                },
            ],
        ],
    ];
    ?>


    <div class="form-section">
        <div class="form-section_item">
            <div class="d-flex justify-content-between align-items-center">
                <p><b>Jami soni: &nbsp; <?= $dataProvider->totalCount."&nbsp;&nbsp; | &nbsp;&nbsp;".number_format($totalSum, 0, '', ' ') ?> so'm</b></p>
                <div class="page_export d-flex align-items-center gap-4">
                    <div>
                        <?php echo \kartik\export\ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $data,
                            'asDropdown' => false,
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $data,
    ]); ?>


</div>


    <div class="modal fade" id="studentInfoDate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="form-section">
                    <div class="form-section_item">
                        <div class="modal-body" id="studentInfoBodyDate">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$js = <<<JS
$(document).ready(function() {
    $('#studentInfo').on('show.bs.modal', function (e) {
        $(this).find('#studentInfoBody').empty();
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentInfoBody').load(url);
    });
    
    $('#studentInfoDate').on('show.bs.modal', function (e) {
        // $(this).find('#studentInfoBody').empty();
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentInfoBodyDate').load(url);
    });
});
JS;
$this->registerJs($js);
?>