<?php
/** @var \common\models\Student $student */
?>
<div class="ika_content_left_item">
    <div class="sidebar-top">
        <div class="sidebar-left-right">
            <div class="sidebar-left">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="sidebar-right">
                <h6 class="mt-1"><?= $student->fullName ?></h6>
                <p class="mt-1">ID: <span>&nbsp; <?= $student->user_id ?></span></p>
            </div>
        </div>
    </div>
    <div class="sidebar-content mb-4">
        <ul>
            <li>
                <a href="/cabinet/index" class="<?= getActive('cabinet', 'index') ?>">
                    <span><i class="bi bi-signpost"></i></span>
                    <span class="sidebar-link-test"><?= Yii::t("app", "a40") ?></span>
                </a>
            </li>

            <?php if ($student->edu_type_id == 1) : ?>
                <li>
                    <a href="/cabinet/exam" class="<?= getActive('cabinet', 'exam') ?>">
                        <span><i class="bi bi-graph-up"></i></span>
                        <span class="sidebar-link-test"><?= Yii::t("app", "a205") ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <li>
                <a href="/cabinet/download-file" class="<?= getActive('cabinet', 'download-file') ?>">
                    <span><i class="fa-solid fa-file-arrow-down"></i></span>
                    <span class="sidebar-link-test"><?= Yii::t("app", "a206") ?></span>
                </a>
            </li>

            <li>
                <a href="/cabinet/send-file" class="<?= getActive('cabinet', 'send-file') ?>">
                    <span><i class="bi bi-credit-card"></i></span>
                    <span class="sidebar-link-test"><?= Yii::t("app", "a207") ?></span>
                </a>
            </li>

            <li>
                <a href="/site/logout" data-method="post">
                    <span><i class="bi bi-box-arrow-right"></i></span>
                    <span class="sidebar-link-test"><?= Yii::t("app", "a41") ?></span>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-bottom mb-4">
        <div class="sidebar-bottom-left">
            <i class="bi bi-question-circle"></i>
        </div>
        <div class="sidebar-bottom-right">
            <p><?= Yii::t("app", "a208") ?></p>
            <a href="https://t.me/sarbonuniversity"><?= Yii::t("app", "a209") ?></a>
        </div>
    </div>
</div>