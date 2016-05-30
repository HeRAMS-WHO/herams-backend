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
$formatter = app()->formatter;

/** @var \SamIT\LimeSurvey\Interfaces\ResponseInterface $response */

$this->beginContent('@app/views/layouts/report.php');

$number = (int) $generator->getQuestionValue('gi1');
$from = (new \Carbon\Carbon($generator->getQuestionValue('gi2')))->format($generator->dateFormat);
$until = (new \Carbon\Carbon($generator->getQuestionValue('gi3')))->format($generator->dateFormat);
?>

<style>
    <?=file_get_contents(__DIR__ . '/../../base/assets/css/grid.css')?>
    <?php include __DIR__ . '/../../base/assets/css/style.php'; ?>

    .header {
        background-image: url(data:image/png;base64,<?=base64_encode(file_get_contents(__DIR__ . '/../../base/assets/img/who-logo.png'))?>);
        background-position: right;
        background-repeat: no-repeat;
        background-size: contain;
        height: 45px;
    }

    .text-center {
        text-align: center;
    }
    .ap-block img {
        /*max-height: 170px;*/
        max-width: 170px;
    }
    .ap-block > * {
        display: block;
        width: 100%;
        text-align: center;
    }
</style>

<?=$this->render('page1', [
    'generator' => $generator,
    'formatter' => $formatter,
    'project' => $project,
    'number' => $number,
    'from' => $from,
    'until' => $until,
    'signature' => $signature
]); ?>

<?php $generator->beginBlock(); ?>
<div class="container-fluid">
    <?=$this->render('header', ['project' => $project, 'number' => $number, 'from' => $from, 'until' => $until])?>

    <?= $this->render('healthSectorNumbers', ['g' => $generator, 'f' => $formatter]); ?>
    <div class="row no-break">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Situation update')?></h2>
        <div class="col-xs-12"><?=$generator->getQuestionValue('situpHTML')?></div>
    </div>
</div>
<?php $generator->endBlock(); $generator->beginBlock(); ?>
<div class="container-fluid">
    <?=$this->render('header', ['project' => $project, 'number' => $number, 'from' => $from, 'until' => $until])?>

    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Health risks, needs, gaps and priorities')?></h2>
        <div class="col-xs-12"><?=$generator->getQuestionValue('hnp1HTML')?></div>
        <?php if($generator->getQuestionValue('hnp2HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'General clinical services and trauma care')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hnp2HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hnp3HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Child health')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hnp3HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hnp4HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Communicable disease')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hnp4HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hnp5HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Sexual and reproductive health')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hnp5HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hnp6HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Non communicable disease and mental health')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hnp6HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hnp7HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Environmental health / Water and sanitation')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hnp7HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hnp8') == 'Y') { ?>
            <h3 class="col-xs-12"><?=$generator->getQuestionValue('hnp8a')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hnp8HTML')?></div>
        <?php } ?>
    </div>
</div>
<?php $generator->endBlock(); $generator->beginBlock(); ?>
<div class="container-fluid">
    <?=$this->render('header', ['project' => $project, 'number' => $number, 'from' => $from, 'until' => $until])?>

    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Health sector action')?></h2>
        <div class="col-xs-12"><?=$generator->getQuestionValue('hca1HTML')?></div>
        <?php if($generator->getQuestionValue('hca2HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'General clinical services and trauma care')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hca2HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hca3HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Child health')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hca3HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hca4HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Communicable disease')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hca4HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hca5HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Sexual and reproductive health')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hca5HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hca6HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Non communicable disease and mental health')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hca6HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hca7HTML') != '') { ?>
            <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Environmental health / Water and sanitation')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hca7HTML')?></div>
        <?php } ?>
        <?php if($generator->getQuestionValue('hca8') == 'Y') { ?>
            <h3 class="col-xs-12"><?=$generator->getQuestionValue('hca8a')?></h3>
            <div class="col-xs-12"><?=$generator->getQuestionValue('hca8HTML')?></div>
        <?php } ?>
    </div>
</div>
<?php $generator->endBlock(); $generator->beginBlock(); ?>
<style>
    table {
        width: 100%;
    }

    table#resource tr td:not(:first-child), table tr th:not(:first-child) {
        text-align: right;
        padding-right: 10px;
    }

    table#resource tr td, table#resource tr th {
        padding-top: 3px;
        padding-bottom: 3px;
    }

    table#resource > thead {
        color: white;
        background-color: dimgrey;
    }
</style>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project, 'number' => $number, 'from' => $from, 'until' => $until])?>

    <div class="row">
        <?php $generator->beginBlock(); ?>
        <div class="col-xs-12">
            <h2><?=\Yii::t('oscar', 'Resource mobilization')?></h2>
            <?=$generator->getQuestionValue('resmob1HTML')?>
            <?=$this->render('fundingTable', [
                'formatter' => $formatter,
                'generator' => $generator
            ]); ?>
        <?php $generator->endBlock(); $generator->beginBlock(); ?>
        <div class="col-xs-12">
            <h2><?=\Yii::t('oscar', 'Health sector partners')?></h2>
            <?=$generator->getQuestionValue('resmob3')?>
        </div>
        <?php $generator->endBlock(); $generator->beginBlock(); ?>
        <div class="col-xs-12">
            <h2><?=\Yii::t('oscar', 'Background of the crisis')?></h2>
            <?=$generator->getQuestionValue('backgHTML')?>
        </div>
            <?php $generator->endBlock(); ?>
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Contact information')?></h2>
        <?php
        for ($i = 1; $i <= 4; $i++) {
            if ($generator->getQuestionValue('Cont1[SQ00' . $i . '_SQ001]') != '') {
                ?>
                <div class="col-xs-6 col-sm-3">
                    <?=$generator->getQuestionValue('Cont1[SQ00' . $i . '_SQ001]')?>&nbsp;<br>
                    <?=$generator->getQuestionValue('Cont1[SQ00' . $i . '_SQ002]')?>&nbsp;<br>
                    <?=$generator->getQuestionValue('Cont1[SQ00' . $i . '_SQ003]')?>&nbsp;<br>
                    <?=$generator->getQuestionValue('Cont1[SQ00' . $i . '_SQ004]')?>&nbsp;
                </div>
                <?php
            }
        }
        ?>
    </div>
    <?php $generator->beginBlock(); ?>
    <div class="row no-break">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'References')?></h2>
        <div class="col-xs-12">
        <table>
            <?php
            $references = [
                'hi1x' => \Yii::t('oscar', '% of health facilities damaged'),
                'hi2x' => \Yii::t('oscar', '% of functional health facilities'),
                'hi3x' => \Yii::t('oscar', '% of health facilities directly supported by health sector partners'),
                'hri1x' => \Yii::t('oscar', 'Medical supplies delivered'),
                'hri2x' => \Yii::t('oscar', 'Number of consultations'),
                'hri3x' => \Yii::t('oscar', 'Number of surgeries'),
                'hri4x' => \Yii::t('oscar', 'Number of patients referred'),
                'hri5x' => \Yii::t('oscar', 'Number of births assisted by a skilled attendant'),
                'hri6x' => \Yii::t('oscar', 'Number of C-sections'),
                'hri7ax' => \Yii::t('oscar', 'Number of children < 5 y.o. vaccinated against measles'),
                'hri7bx' => \Yii::t('oscar', 'Number of children < 15 y.o. vaccinated against measles'),
                'hri8ax' => \Yii::t('oscar', 'Number of children < 5 y.o. vaccinated against polio'),
                'hri8bx' => \Yii::t('oscar', 'Number of children < 15 y.o. vaccinated against polio'),
                'hri9x' => \Yii::t('oscar', 'Number of children < 1 y.o. who received the third dose of DTP'),
                'ew1x' => \Yii::t('oscar', 'Number of EWARS sentinel sites'),
                'ew2x' => \Yii::t('oscar', '% of EWARS sentinel sites that reported on-time over the reporting period'),
                'ew3x' => \Yii::t('oscar', '% of EWARS sentinel sites that provided complete reports over the reporting period'),
                'resmob2x' => \Yii::t('oscar', 'Funding status (in US$)'),
                'hr1x' => \Yii::t('oscar', 'Number of health sector/cluster actors (i.e. organisations)')
            ];
            foreach($references as $title => $description) {
                $value = $generator->getQuestionValue($title);
                if(isset($value) && $value != '') {
                    echo "<tr><td>{$description}</td><td>{$value}</td></tr>";
                }
            }
            ?>
        </table>
        </div>
    </div>
    <?php $generator->endBlock(); ?>
</div>
<?php $generator->endBlock(); ?>
<?php $this->endContent();