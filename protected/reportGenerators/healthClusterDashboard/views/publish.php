<?php

use app\components\Html;

/**
 * @var \yii\web\View $this;
 * @var \prime\reportGenerators\base\Generator $generator
 */

$this->registerAssetBundle(\prime\assets\SassAsset::class);
$this->beginContent('@app/views/layouts/report.php');
?>

<div class="container-fluid">
<div class="row">

    <div class="col-xs-12" style="margin-bottom: 10px;">
        <div class="row">
            <div class="col-xs-6">
                <div class="row">
                    <div class="col-xs-12">
                        <h4><?=\Yii::t('app', 'Coordinator') ?></h4>
                    </div>
                    <div class="col-xs-12">
                        <?php
                        if (null !== $coordinator = \prime\models\ar\User::find()->where(
                                ['id' => $generator->getQuestionValue("CM05")]
                            )->one()
                        ) {
                            echo \prime\widgets\User::widget([
                                'user' => $coordinator
                            ]);
                        } else {
                            echo \Yii::t('app', 'None');
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="row">
                    <div class="col-xs-12">
                        <h4><?=\Yii::t('app', 'Co-coordinator') ?></h4>
                    </div>
                    <div class="col-xs-12">
                        <?php
                        if (null !== $cocoordinator = \prime\models\ar\User::find()->where(
                                ['id' => $generator->getQuestionValue("CM07")]
                            )->one()
                        ) {
                            echo \prime\widgets\User::widget([
                                'user' => $cocoordinator
                            ]);
                        } else {
                            echo \Yii::t('app', 'None');
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<?php
$this->endContent();
?>