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
</style>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project, 'number' => $number, 'from' => $from, 'until' => $until])?>
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
    <style>
        .ap-img {
            width: 100%;
        }
    </style>

    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Affected population')?></h2>
        <div class="col-xs-12" style="border: 1px solid #666; padding-bottom: 1em;">
        <table style="text-align: center">
            <tr>
                <td><img class="ap-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents(__DIR__ . ' /../assets/img/affected.jpg'))?>"></td>
                <td><img class="ap-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents(__DIR__ . ' /../assets/img/deaths.jpg'))?>"></td>
                <td><img class="ap-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents(__DIR__ . ' /../assets/img/refugees.jpg'))?>"></td>
                <td><img class="ap-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents(__DIR__ . ' /../assets/img/displaced.jpg'))?>"></td>
                <td><img class="ap-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents(__DIR__ . ' /../assets/img/injured.jpg'))?>"></td>
            </tr>
            <tr>
                <td width="20%"><?=\Yii::t('oscar', '# People')?></td>
                <td width="20%"><?=\Yii::t('oscar', '# Deaths')?></td>
                <td width="20%"><?=\Yii::t('oscar', '# Refugees')?></td>
                <td width="20%"><?=\Yii::t('oscar', '# Internally displaced')?></td>
                <td width="20%"><?=\Yii::t('oscar', '# Injured')?></td>
            </tr>
            <tr>
                <td><?=$formatter->asInteger($generator->getQuestionValue('genindic[SQ001]'))?></td>
                <td><?=$formatter->asInteger($generator->getQuestionValue('genindic[SQ002]'))?></td>
                <td><?=$formatter->asInteger($generator->getQuestionValue('genindic[SQ003]'))?></td>
                <td><?=$formatter->asInteger($generator->getQuestionValue('genindic[SQ004]'))?></td>
                <td><?=$formatter->asInteger($generator->getQuestionValue('genindic[SQ005]'))?></td>
            </tr>
        </table>
        </div>
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Highlights')?></h2>
        <div class="col-xs-12"><?=$generator->getQuestionValue('highlHTML')?></div>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project, 'number' => $number, 'from' => $from, 'until' => $until])?>

    <style>
        .hcin-img-cont {
            padding-right: 0px;
        }

        .hcin-img {
            width: 100%;
            margin-top: 0.8em;
        }

        .hcin-numbers {
            font-size: 0.9em;
            text-align: right;
            padding: 0px;
        }

        .hcin-text {
            font-size: 0.9em;
        }

        .hcin-title {
            margin-bottom: 0px;
        }
    </style>

    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Health Sector in numbers')?></h2>
        <div class="col-xs-12" style="border: 1px solid #666; padding-bottom: 1em;">
            <div class="row">
                <div class="col-xs-6">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-2 hcin-img-cont">
                                    <img class="hcin-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents(__DIR__ . ' /../assets/img/partners.jpg'))?>">
                                </div>
                                <div class="col-xs-10">
                                    <h3 class="hcin-title"><?=\Yii::t('oscar', 'Partners')?></h3>
                                    <div class="row">
                                        <div class="col-xs-2 hcin-numbers">
                                            <?=(int) $generator->getQuestionValue('hr1[1]')?><br>
                                            <?=(int) $generator->getQuestionValue('hr1[2]')?><br>
                                            <?=(int) $generator->getQuestionValue('hr1[3]')?><br>
                                            <?=(int) $generator->getQuestionValue('hr1[4]')?><br>
                                            <?=(int) $generator->getQuestionValue('hr1[5]')?><br>
                                            <?=(int) $generator->getQuestionValue('hr1[6]')?><br>
                                        </div>
                                        <div class="col-xs-10 hcin-text">
                                            <?=\Yii::t('oscar', 'International NGOs')?><br>
                                            <?=\Yii::t('oscar', 'National NGOs')?><br>
                                            <?=\Yii::t('oscar', 'UN Agencies')?><br>
                                            <?=\Yii::t('oscar', 'National Authorities')?><br>
                                            <?=\Yii::t('oscar', 'Donors')?><br>
                                            <?=\Yii::t('oscar', 'Other')?><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-2 hcin-img-cont">
                                    <img class="hcin-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents(__DIR__ . ' /../assets/img/health facilities.jpg'))?>">
                                </div>
                                <div class="col-xs-10">
                                    <h3 class="hcin-title"><?=\Yii::t('oscar', 'Health infrastructure')?></h3>
                                    <div class="row">
                                        <div class="col-xs-2 hcin-numbers">
                                            <?php
                                            $series = [
                                                \Yii::t('oscar', 'Damaged / destroyed') => (int) $generator->getQuestionValue('hi1') . '%' ,
                                                \Yii::t('oscar', 'Functioning') => (int) $generator->getQuestionValue('hi2') . '%',
                                                \Yii::t('oscar', 'Supported by health partners') => (int) $generator->getQuestionValue('hi3') . '%'
                                            ];
                                            foreach($series as $key => $value) {
                                                if(isset($value) && $value != '' && $value != '%') {
                                                    echo $value . '<br>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="col-xs-10 hcin-text">
                                            <?php
                                            foreach($series as $key => $value) {
                                                if(isset($value) && $value != '' && $value != '%') {
                                                    echo $key . '<br>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-2 hcin-img-cont">
                                    <img class="hcin-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents(__DIR__ . ' /../assets/img/ewarn.jpg'))?>">
                                </div>
                                <div class="col-xs-10">
                                    <h3 class="hcin-title"><?=\Yii::t('oscar', 'EWARS')?></h3>
                                    <div class="row">
                                        <div class="col-xs-2 hcin-numbers">
                                            <?php
                                            $series = [
                                                \Yii::t('oscar', 'Sentinel sites') => $generator->getQuestionValue('ew1[other]'),
                                                \Yii::t('oscar', 'Reported timely') => $generator->getQuestionValue('ew3[other]') . '%',
                                                \Yii::t('oscar', 'Provided complete reports') => $generator->getQuestionValue('ew2[other]') . '%'
                                            ];
                                            foreach($series as $key => $value) {
                                                if(isset($value) && $value != '' && $value != '%') {
                                                    echo $value . '<br>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="col-xs-10 hcin-text">
                                            <?php
                                            foreach($series as $key => $value) {
                                                if(isset($value) && $value != '' && $value != '%') {
                                                    echo $key . '<br>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-2 hcin-img-cont">
                                    <img class="hcin-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents(__DIR__ . ' /../assets/img/health action.jpg'))?>">
                                </div>
                                <div class="col-xs-10">
                                    <h3 class="hcin-title"><?=\Yii::t('app', 'Health interventions')?></h3>
                                    <div class="row">
                                        <div class="col-xs-2 hcin-numbers">
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
                                                    echo $value . '<br>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="col-xs-10 hcin-text">
                                            <?php
                                            foreach($series as $key => $value) {
                                                if(isset($value) && $value != '') {
                                                    echo $key . '<br>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-2 hcin-img-cont">
                                    <img class="hcin-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents(__DIR__ . ' /../assets/img/funding.jpg'))?>">
                                </div>
                                <div class="col-xs-10">
                                    <h3 class="hcin-title"><?=\Yii::t('app', 'Funding')?></h3>
                                    <div class="row">
                                        <div class="col-xs-2 hcin-numbers">
                                            <?php
                                            $series = [
                                                \Yii::t('oscar', 'WHO Funded') => $generator->getPercentage($generator->response, 'resmob2[rmwho_SQ002]', 'resmob2[rmwho_SQ001]'),
                                                \Yii::t('oscar', 'Health sector funded') => $generator->getPercentage($generator->response, 'resmob2[rmhc_SQ002]', 'resmob2[rmhc_SQ001]'),
                                                \Yii::t('oscar', 'Overall funded') => round(($generator->getQuestionValue('resmob2[rmhc_SQ002]') + $generator->getQuestionValue('resmob2[rmwho_SQ002]')) * 100 / ($generator->getQuestionValue('resmob2[rmhc_SQ001]') + $generator->getQuestionValue('resmob2[rmwho_SQ001]')))
                                            ];
                                            foreach($series as $key => $value) {
                                                if(isset($value) && $value != 0) {
                                                    echo $value . '%<br>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="col-xs-10 hcin-text">
                                            <?php
                                            foreach($series as $key => $value) {
                                                if(isset($value) && $value != '') {
                                                    echo $key . '<br>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row no-break">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Situation update')?></h2>
        <div class="col-xs-12"><?=$generator->getQuestionValue('situpHTML')?></div>
    </div>
</div>

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
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Resource mobilization')?></h2>
        <div class="col-xs-12"><?=$generator->getQuestionValue('resmob1HTML')?></div>
        <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Funding (in US$)')?></h3>
        <div class="col-xs-12">
            <table id="resource">
                <thead>
                    <tr>
                        <th></th>
                        <th><?=\Yii::t('oscar', 'Required')?></th>
                        <th><?=\Yii::t('oscar', 'Funded')?></th>
                        <th><?=\Yii::t('oscar', '% funded')?></th>
                    </tr>
                </thead>
                <tr class="text-right">
                    <td><?=\Yii::t('oscar', 'WHO')?></td>
                    <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmwho_SQ001]'))?></td>
                    <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmwho_SQ002]'))?></td>
                    <td><?=$formatter->asPercent(
                            $generator->getPercentage($generator->response, 'resmob2[rmwho_SQ002]', 'resmob2[rmwho_SQ001]') / 100
                        )?></td>
                </tr>
                <tr>
                    <td><?=\Yii::t('oscar', 'Health sector')?></td>
                    <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmhc_SQ001]'))?></td>
                    <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmhc_SQ002]'))?></td>
                    <td><?=$formatter->asPercent(
                            $generator->getPercentage($generator->response, 'resmob2[rmhc_SQ002]', 'resmob2[rmhc_SQ001]') / 100
                        )?></td>
                </tr>
                <tr>
                    <td><?=\Yii::t('oscar', 'Total')?></td>
                    <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmwho_SQ001]') + $generator->getQuestionValue('resmob2[rmhc_SQ001]'))?></td>
                    <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmwho_SQ002]') + $generator->getQuestionValue('resmob2[rmhc_SQ002]'))?></td>
                    <td><?=$formatter->asPercent(($generator->getQuestionValue('resmob2[rmhc_SQ002]') + $generator->getQuestionValue('resmob2[rmwho_SQ002]')) / ($generator->getQuestionValue('resmob2[rmhc_SQ001]') + $generator->getQuestionValue('resmob2[rmwho_SQ001]')))?></td>
                </tr>
            </table>
        </div>
        <div class="col-xs-12">
            <h2><?=\Yii::t('oscar', 'Health sector partners')?></h2>
            <?=$generator->getQuestionValue('resmob3')?>
        </div>

        <div class="col-xs-12">
            <h2><?=\Yii::t('oscar', 'Background of the crisis')?></h2>
            <?=$generator->getQuestionValue('backgHTML')?>
        </div>

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
</div>

<?php $this->endContent(); ?>