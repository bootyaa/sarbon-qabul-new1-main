<?php

use yii\helpers\Html;

use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\User;
use kartik\grid\ExpandRowColumn;

/** @var yii\web\View $this */
/** @var common\models\Student $model */

$this->title = 'Imtihon natijalari';

$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => $model->eduType->name_uz,
    'url' => ['index', 'id' => $model->edu_type_id],
];
$breadcrumbs['item'][] = [
    'label' => $model->fullName,
    'url' => ['view', 'id' => $model->id],
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

    <div class="page-item mb-4">
        <div class="row">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'class' => ExpandRowColumn::class,
                        'width' => '50px',
                        'value' => function () {
                            return GridView::ROW_COLLAPSED;
                        },
                        'detail' => function ($model) {
                            $histories = $model->histories;
                            if (empty($histories)) {
                                return "<i>O‘zgarishlar mavjud emas.</i>";
                            }

                            $html = '<table class="table table-bordered table-sm">';
                            $html .= '<thead><tr><th>Maydon</th><th>Eski</th><th>Yangi</th><th>Vaqt</th></tr></thead><tbody>';

                            foreach ($histories as $history) {
                                $data = is_string($history->data)
                                    ? json_decode($history->data, true)
                                    : $history->data;

                                foreach ($data as $attr => $change) {
                                    $html .= '<tr>';
                                    $html .= '<td><b>' . Html::encode($attr) . '</b></td>';
                                    $html .= '<td style="color:red;">' . Html::encode($change['old']) . '</td>';
                                    $html .= '<td style="color:green;">' . Html::encode($change['new']) . '</td>';
                                    $html .= '<td>' . Yii::$app->formatter->asDatetime($history->created_at) . '</td>';
                                    $html .= '</tr>';
                                }
                            }

                            $html .= '</tbody></table>';
                            return $html;
                        },
                        'expandOneOnly' => true,
                    ],

                    // [
                    //     'attribute' => 'question_id',
                    //     'label' => 'Savol',
                    //     'format' => 'ntext',
                    //     'value' => fn($model) => isset($model->question->text)
                    //         ? trim(html_entity_decode(strip_tags($model->question->text)))
                    //         : '(yo‘q)',
                    // ],
                    [
                        'attribute' => 'question_text',
                        'label' => 'Savol',
                        'format' => 'ntext',
                        'value' => fn($model) => isset($model->question->text)
                            ? trim(html_entity_decode(strip_tags($model->question->text)))
                            : '(yo‘q)',
                    ],
                    // [
                    //     'attribute' => 'option_id',
                    //     'label' => 'Tanlangan javob',
                    //     'format' => 'raw',
                    //     'value' => fn($model) =>
                    //     $model->chooseOption
                    //         ? trim(html_entity_decode(strip_tags($model->chooseOption->text)))
                    //         : '<span class="text-muted">Tanlanmagan</span>',
                    // ],
                    // [
                    //     'attribute' => 'option',
                    //     'label' => 'Tanlangan javob',
                    //     'format' => 'raw',
                    // ],
                    // [
                    //     'attribute' => 'option',
                    //     'label' => 'Tanlangan javob',
                    //     'format' => 'raw',
                    //     'value' => function ($model) {
                    //         if (!$model->question) return '<span class="text-muted">—</span>';

                    //         $selectedIds = [];
                    //         $decoded = json_decode($model->option, true);
                    //         if (is_array($decoded)) {
                    //             foreach ($decoded as $item) {
                    //                 if (isset($item['id'])) {
                    //                     $selectedIds[] = $item['id'];
                    //                 }
                    //             }
                    //         }

                    //         if (empty($selectedIds)) return '<span class="text-muted">—</span>';

                    //         $output = '';
                    //         foreach ($model->question->options as $option) {
                    //             if (in_array($option->id, $selectedIds)) {
                    //                 $output .= $option->text;
                    //             }
                    //         }

                    //         return $output ?: '<span class="text-muted">—</span>';
                    //     },
                    // ],
                    [
                        'attribute' => 'option_id',
                        'label' => 'Tanlangan javob',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (!$model->question || empty($model->question->options)) {
                                return '<span class="text-muted">—</span>';
                            }

                            $output = '<div class="d-inline-block p-2" style="background-color:#e9ecef;border-radius:8px;">';

                            foreach ($model->question->options as $option) {
                                $text = $option->text;
                                $isSelected = $option->id == $model->option_id;
                                $isCorrect = $option->is_correct == 1;

                                if ($isSelected && $isCorrect) {
                                    $class = 'bg-success text-white';
                                } elseif ($isSelected && !$isCorrect) {
                                    $class = 'bg-danger text-white';
                                } elseif ($isCorrect) {
                                    $class = 'bg-secondary text-white';
                                } else {
                                    $class = 'bg-light text-dark border';
                                }
                                // $output .= "<div class='badge $class me-1 mb-1 d-flex align-items-center gap-1' style='font-size:13px;'><span style='font-weight:bold;'>ID:{$option->id})</span> $text</div>";


                                // $output .= "<div class='badge $class me-1 mb-1' style='font-size:13px; display: inline-block;'>
                                // <span style='font-weight:bold;'>ID:{$option->id}</span>&nbsp;$text
                                // </div>";

                                $output .= "<div class='badge $class me-1 mb-1' style='font-size:13px;'><span>$text</span></div>";
                            }

                            $output .= '</div>';
                            return $output;
                        },
                    ],


                    [
                        'attribute' => 'is_correct',
                        'label' => 'To‘g‘rimi?',
                        'format' => 'raw',
                        'filter' => [1 => "To'g'ri", 0 => "Noto'g'ri"],
                        'value' => fn($model) => $model->is_correct
                            ? '<span class="badge bg-success">To‘g‘ri</span>'
                            : '<span class="badge bg-danger">Noto‘g‘ri</span>',
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Holati',
                        'format' => 'raw',
                        'filter' => [1 => "Faol", 0 => "Faol emas"],
                        'value' => fn($model) => match ($model->status) {
                            1 => '<span class="badge bg-success">Faol</span>',
                            0 => '<span class="badge bg-warning text-dark">Faol emas</span>',
                            default => '<span class="badge bg-secondary">Noma’lum</span>',
                        },
                    ],
                    [
                        'attribute' => 'is_deleted',
                        'label' => 'O‘chirilganmi',
                        'format' => 'raw',
                        'filter' => [0 => 'Yo‘q', 1 => 'Ha'],
                        'value' => fn($model) => $model->is_deleted == 1
                            ? '<span class="badge bg-danger">O‘chirilgan</span>'
                            : '<span class="badge bg-primary">Saqlangan</span>',
                    ],
                    [
                        'attribute' => 'created_by',
                        'label' => 'Yaratgan',
                        'format' => 'raw',
                        'filter' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
                        'value' => fn($model) =>
                        $model->createdBy
                            ? '<span class="badge bg-success">' . Html::encode($model->createdBy->username) . '</span>'
                            : '<span class="badge bg-secondary">nomalum</span>',
                    ],
                    [
                        'attribute' => 'updated_by',
                        'label' => 'O‘zgartirgan',
                        'format' => 'raw',
                        'filter' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
                        'value' => fn($model) =>
                        $model->updatedBy
                            ? '<span class="badge bg-info">' . Html::encode($model->updatedBy->username) . '</span>'
                            : '<span class="badge bg-secondary">nomalum</span>',
                    ],
                ],
            ]) ?>
        </div>
    </div>


</div>




<?php
$js = <<<JS
$(document).ready(function() {
    
});
JS;
$this->registerJs($js);
?>