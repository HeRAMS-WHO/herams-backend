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

<style>
    <?=file_get_contents(__DIR__ . '/../../base/assets/css/grid.css')?>
    <?php include __DIR__ . '/../../base/assets/css/style.php'; ?>

    .text-center {
        text-align: center;
    }
</style>

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

<div class="container-fluid">
    <?php vd($generator->response);?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Highlights')?></h2>
        <div class="col-xs-12"><?=$generator->getQuestionValue('highlHTML')?></div>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Health Sector in numbers')?></h2>
        <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Partners')?></h3>
        <div class="col-xs-12">Where to get from?</div>

        <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Health infrastructure')?></h3>
        <div class="col-sm-4">
            <h4><?=\Yii::t('oscar', 'Damaged / destroyed')?></h4>
            <?=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('hi1')])?>
        </div>
        <div class="col-sm-4">
            <h4><?=\Yii::t('oscar', 'Functioning')?></h4>
            <?=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('hi2')])?>
        </div>
        <div class="col-sm-4">
            <h4><?=\Yii::t('oscar', 'Supported by health partners')?></h4>
            <?=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('hi3')])?>
        </div>

        <h3 class="col-xs-12"><?=\Yii::t('oscar', 'EWARS')?></h3>
        <?php if($generator->getQuestionValue('ew1') == '-oth-') { ?>
        <div class="col-sm-4">
            <h4>&nbsp;</h4>
            <h1 class="text-center"><br><?=$generator->getQuestionValue('ew1[other]')?></h1>
            <h2 class="text-center"><?=\Yii::t('oscar', 'Sentinel sites')?></h2>
        </div>
        <?php } ?>
        <?php if($generator->getQuestionValue('ew3') == '-oth-') { ?>
        <div class="col-sm-4">
            <h4><?=\Yii::t('oscar', 'Reported timely')?></h4>
            <?=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('ew3[other]')])?>
        </div>
        <?php } ?>
        <?php if($generator->getQuestionValue('ew2') == '-oth-') { ?>
        <div class="col-sm-4">
            <h4><?=\Yii::t('oscar', 'Provided complete reports')?></h4>
            <?=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('ew2[other]')])?>
        </div>
        <?php } ?>

        <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Health interventions')?></h3>
        <?php
        $series = [
            \Yii::t('oscar', 'Medicines delivered') => $generator->getQuestionValue('hri1[other]'),
            \Yii::t('oscar', 'Consultations provided') => $generator->getQuestionValue('hri2[other]'),
            \Yii::t('oscar', 'Surgical interventions') => $generator->getQuestionValue('hri3[other]'),
            \Yii::t('oscar', 'Patients referred') => $generator->getQuestionValue('hri4[other]'),
            \Yii::t('oscar', 'Births assisted') => $generator->getQuestionValue('hri5[other]'),
            \Yii::t('oscar', 'C-Sections') => $generator->getQuestionValue('hri6[other]'),
            \Yii::t('oscar', 'Measles vaccinations (<5 y.o)') => $generator->getQuestionValue('hri7a[other]'),
            \Yii::t('oscar', 'Measles vaccinations (<15 y.o)') => $generator->getQuestionValue('hri7b[other]'),
            \Yii::t('oscar', 'Polio vaccinations (<5 y.o)') => $generator->getQuestionValue('hri8a[other]'),
            \Yii::t('oscar', 'Polio vaccinations (<15 y.o)') => $generator->getQuestionValue('hri8b[other]'),
            \Yii::t('oscar', '3th doses of DTP (<1 y.o)') => $generator->getQuestionValue('hri9[other]'),
        ];
        foreach($series as $key => $value) {
            if(isset($value) && $value != '') {
                ?>
                <div class="col-sm-4">
                    <h4>&nbsp;</h4>
                    <h1 class="text-center"><br><?=$value?></h1>
                    <h2 class="text-center"><?=$key?></h2>
                </div>
                <?php
            }
        }
        ?>
        <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Funding')?></h3>
        <div class="col-xs-12"><?=$generator->getQuestionValue('resmob1HTML')?></div>
<!--        --><?php //if($generator->getQuestionValue('ew3') == '-oth-') { ?>
<!--            <div class="col-sm-4">-->
<!--                <h4>--><?//=\Yii::t('oscar', 'Reported timely')?><!--</h4>-->
<!--                --><?//=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('ew3[other]')])?>
<!--            </div>-->
<!--        --><?php //} ?>
        <div class="col-sm-4">
            <table>
                <tr>
                    <td></td>
                    <td><?=\Yii::t('oscar', 'WHO')?></td>
                    <td><?=\Yii::t('oscar', 'Health Sector')?></td>
                </tr>
                <tr>
                    <td><?=\Yii::t('oscar', 'Required')?></td>
                    <td>$ <?=$generator->getQuestionValue('resmob2[rmwho_SQ001]')?></td>
                    <td>$ <?=$generator->getQuestionValue('resmob2[rmhc_SQ001]')?></td>
                </tr>
                <tr>
                    <td><?=\Yii::t('oscar', 'Funded')?></td>
                    <td>$ <?=$generator->getQuestionValue('resmob2[rmwho_SQ002]')?></td>
                    <td>$ <?=$generator->getQuestionValue('resmob2[rmhc_SQ002]')?></td>
                </tr>
            </table>
        </div>
    </div>
</div>


<?php $this->endContent(); ?>