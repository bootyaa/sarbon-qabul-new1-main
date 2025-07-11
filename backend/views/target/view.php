<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use Da\QrCode\QrCode;

/** @var yii\web\View $this */
/** @var common\models\Target $model */

$this->title = $model->name;
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Target'),
    'url' => ['index'],
];
$cons = $model->cons;
$domen = $cons->domen;
\yii\web\YiiAsset::register($this);
?>
<div class="target-view">

    <div class="grid-view">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
            ],
        ]) ?>
    </div>

    <div class="form-section mt-3">
        <div class="form-section_item">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12 mt-2">
                    <?php
                    $url = "https://".$domen."?id=".$model->id;
                    ?>
                    <h6 class="badge-table-div active"><?= $url ?></h6>
                    <div class="mt-3">
                        <?php
                        $lqr = (new QrCode($url))->setSize(300, 300)
                            ->setMargin(5);
                        $limg = $lqr->writeDataUri();
                        ?>
                        <img src="<?= $limg ?>" width="200px">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-12 mt-2">
                    <?php
                    $url2 = "https://".$domen."/site/sign-up?id=".$model->id;
                    ?>
                    <h6 class="badge-table-div active"><?= $url2 ?></h6>
                    <div class="mt-3">
                        <?php
                        $lqr2 = (new QrCode($url2))->setSize(300, 300)
                            ->setMargin(5);
                        $limg2 = $lqr2->writeDataUri();
                        ?>
                        <img src="<?= $limg2 ?>" width="200px">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
