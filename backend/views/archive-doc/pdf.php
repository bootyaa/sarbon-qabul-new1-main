<?php
use yii\helpers\Html;

/** @var app\models\ArchiveDoc $model */

$eduDirection = \common\models\EduDirection::findOne($model->edu_direction_id);
$subject = \common\models\ExamSubject::find()
    ->where([
        'edu_direction_id' => $model->edu_direction_id,
        'student_id' => $model->student_id,
        'file_status' => 2,
        'is_deleted' => 0
    ])
    ->andWhere(['is_deleted' => 0])
    ->exists();
$sertificate = "YO'Q";
if ($subject) {
    $sertificate = "HA";
}
?>

<style>
    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt;
    }

    h3, h4 {
        text-align: center;
        margin: 5px 0;
    }

    .info-table, .docs-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
    }

    .info-table td {
        border: 1px solid #e9ecef;
        padding: 6px 8px;
    }

    .docs-table th, .docs-table td {
        border: 1px solid #e9ecef;
        padding: 5px 10px;
        text-align: left;
    }

    .docs-table th {
        text-align: center;
    }
</style>

<h3>O‘ZBEKISTON RESPUBLIKASI OLIY TA’LIM, FAN VA INNOVATSIYALAR VAZIRLIGI</h3>
<h4>SARBON UNIVERSITETI</h4>

<table class="info-table" style="margin-top: 100px">
    <tr>
        <td><strong>Ta’lim yo‘nalishi:</strong></td>
        <td><?= Html::encode($model->direction) ?></td>
    </tr>
    <tr>
        <td><strong>Ta’lim shakli:</strong></td>
        <td><?= Html::encode($model->edu_form) ?></td>
    </tr>
    <tr>
        <td><strong>Ta’lim tili:</strong></td>
        <td><?= Html::encode($eduDirection->lang->name_uz) ?></td>
    </tr>
    <tr>
        <td><strong>Talaba FIO:</strong></td>
        <td><?= Html::encode($model->student_full_name) ?></td>
    </tr>
    <tr>
        <td><strong>Tel raqami:</strong></td>
        <td><?= Html::encode($model->phone_number) ?></td>
    </tr>
    <tr>
        <td><strong>Hujjat topshirilgan sana:</strong></td>
        <td><?= Yii::$app->formatter->asDate($model->submission_date, 'php:Y-m-d') ?></td>
    </tr>
</table>

<h4 style="margin-top: 100px">HUJJATLAR RO‘YXATI</h4>

<table class="docs-table">
    <tr>
        <th>№</th>
        <th>Hujjat nomi</th>
        <th>Holat</th>
    </tr>
    <tr>
        <td>1</td>
        <td>Rektor nomiga ariza</td>
        <td><?= $model->application_letter ? 'HA' : 'YO‘Q' ?></td>
    </tr>
    <tr>
        <td>2</td>
        <td>Passport nusxasi</td>
        <td><?= $model->passport_copy ? 'HA' : 'YO‘Q' ?></td>
    </tr>
    <tr>
        <td>3</td>
        <td>Diplom yoki attestat(ilovasi) asl nusxa</td>
        <td><?= $model->diploma_original ? 'HA' : 'YO‘Q' ?></td>
    </tr>
    <tr>
        <td>4</td>
        <td>3x4 rasm</td>
        <td><?= $model->photo_3x4 ? 'HA' : 'YO‘Q' ?></td>
    </tr>
    <tr>
        <td>5</td>
        <td>Shartnoma nusxasi</td>
        <td><?= $model->contract_copy ? 'HA' : 'YO‘Q' ?></td>
    </tr>
    <tr>
        <td>6</td>
        <td>To‘lov cheki</td>
        <td><?= $model->payment_receipt ? 'HA' : 'YO‘Q' ?></td>
    </tr>
    <tr>
        <td>7</td>
        <td>Fanlardan sertifikat</td>
        <td><?= $sertificate ?></td>
    </tr>
</table>
