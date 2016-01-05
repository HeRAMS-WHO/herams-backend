<?php

use app\components\Html;
use yii\helpers\ArrayHelper;
use app\components\Form;

/**
 * @var \prime\models\ar\UserData $userData
 * @var \yii\web\View $this
 * @var \prime\reportGenerators\oscar\Generator $generator
 * @var \prime\interfaces\ProjectInterface $project
 * @var \prime\interfaces\SignatureInterface $signature
 * @var \prime\interfaces\ResponseCollectionInterface $responses
 */

$generator = $this->context;

/** @var \SamIT\LimeSurvey\Interfaces\ResponseInterface $response */

$this->beginContent('@app/views/layouts/report.php');
?>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h1 class="col-xs-12"><?=$project->getLocality()?></h1>
    </div>
    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            \Yii::t('oscar', 'Level : {level}', ['level' => 'National']) . '<br>' . \Yii::t('oscar', 'Completed on: {completedOn}', ['completedOn' => $signature->getTime()->format($generator->dateFormat)]),
        ],
        'columnsInRow' => 2
    ]);
    ?>
    <hr>
    <div class="row">
        <h1 style="margin-top: 300px; margin-bottom: 300px; text-align: center;"><?=\Yii::t('oscar', 'Final report')?></h1>
    </div>
</div>


<?php $this->endContent(); ?>