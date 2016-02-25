<?php

use app\components\Html;

/**
 * @var \yii\web\View $this;
 */

$this->registerAssetBundle(\prime\assets\SassAsset::class);
$this->beginContent('@app/views/layouts/report.php');
$lastHealthClusterResponse = $this->context->response;
?>

<div class="container-fluid">
<div class="row">

    <div class="col-xs-12" style="margin-bottom: 10px;">
        <div class="row">
            <div class="col-xs-3">
                <?= \Yii::t('app', 'Coordinator:') ?>
            </div>
            <div class="col-xs-3">
                <?php
                // Todo: Possibly refactor to not do queries in view.
                if (null !== $coordinator = \prime\models\ar\User::find()->where(
                        ['id' => $lastHealthClusterResponse->getData()["CM05"]]
                    )->one()
                ) {
                    echo implode(
                        '<br>',
                        [
                            $coordinator->profile->first_name . ' ' . $coordinator->profile->last_name,
                            $coordinator->email,
                            $coordinator->profile->organization
                        ]
                    );
                } else {
                    echo \Yii::t('app', 'none');
                }
                ?>
            </div>
            <div class="col-xs-3">
                <?= \Yii::t('app', 'Co-coordinator:') ?>
            </div>
            <div class="col-xs-3">
                <?php
                // Todo: Possibly refactor to not do queries in view.
                if (null !== $coordinator = \prime\models\ar\User::find()->where(
                        ['id' => $lastHealthClusterResponse->getData()["CM07"]]
                    )->one()
                ) {
                    echo implode(
                        '<br>',
                        [
                            $coordinator->profile->first_name . ' ' . $coordinator->profile->last_name,
                            $coordinator->email,
                            $coordinator->profile->organization
                        ]
                    );
                } else {
                    echo \Yii::t('app', 'none');
                }
                ?>
            </div>
        </div>
    </div>

</div>
</div>
<?php
$this->endContent();
?>