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
    <div class="row">
        <h1 style="margin-top: 300px; margin-bottom: 300px; text-align: center;"><?=\Yii::t('oscar', 'Final report')?></h1>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project, 'number' => $number, 'from' => $from, 'until' => $until])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Affected population')?></h2>
        <div class="col-xs-12">
        <table style="text-align: center">
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

    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Health Sector in numbers')?></h2>
        <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Partners')?></h3>
        <div class="col-xs-12">
            <?php
            echo \miloschuman\highcharts\Highcharts::widget([
                'options' => [
                    'chart' => [
                        'type' => 'bar',
                        'backgroundColor' => 'transparent'
                    ],
                    'title' => [
                        false
                    ],
                    'yAxis' => [
                        'title' => false
                    ],
                    'xAxis' => [
                        'type' => 'category'
                    ],
                    'series' => [
                        [
                            'data' => [
                                [
                                    'name' => \Yii::t('oscar', 'International NGOs'),
                                    'y' => (int) $generator->getQuestionValue('hr1[1]')
                                ],
                                [
                                    'name' => \Yii::t('oscar', 'National NGOs'),
                                    'y' => (int) $generator->getQuestionValue('hr1[2]')
                                ],
                                [
                                    'name' => \Yii::t('oscar', 'UN Agencies'),
                                    'y' => (int) $generator->getQuestionValue('hr1[3]')
                                ],
                                [
                                    'name' => \Yii::t('oscar', 'National Authorities'),
                                    'y' => (int) $generator->getQuestionValue('hr1[4]')
                                ],
                                [
                                    'name' => \Yii::t('oscar', 'Donors'),
                                    'y' => (int) $generator->getQuestionValue('hr1[5]')
                                ],
                                [
                                    'name' => \Yii::t('oscar', 'Other'),
                                    'y' => (int) $generator->getQuestionValue('hr1[6]')
                                ],
                            ],
                            'dataLabels' => [
                                'enabled' => true,
                                'formatter' => new \yii\web\JsExpression('function(){return this.y; return this.y + " " + this.key;}'),
                            ],
                            'animation' => false,
                            'color' => '#EC781C',
                        ]
                    ],
                    'legend' => [
                        'enabled' => false
                    ],
                    'credits' => ['enabled' => false],
                    'tooltip' => [
                        'enabled' => false
                    ]
                ],
                'view' => $this,
                'htmlOptions' => [
                    'style' => [
                        'height' => '220px'
                    ]
                ]
            ]);
            ?>
        </div>

        <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Health infrastructure')?></h3>
        <div class="col-xs-4">
            <h4><?=\Yii::t('oscar', 'Damaged / destroyed')?></h4>
            <?=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('hi1')])?>
        </div>
        <div class="col-xs-4">
            <h4><?=\Yii::t('oscar', 'Functioning')?></h4>
            <?=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('hi2')])?>
        </div>
        <div class="col-xs-4">
            <h4><?=\Yii::t('oscar', 'Supported by health partners')?></h4>
            <?=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('hi3')])?>
        </div>

        <h3 class="col-xs-12"><?=\Yii::t('oscar', 'EWARS')?></h3>
        <?php if($generator->getQuestionValue('ew1') == '-oth-') { ?>
        <div class="col-xs-4">
            <h4>&nbsp;</h4>
            <h1 class="text-center"><?=$generator->getQuestionValue('ew1[other]')?></h1>
            <h2 class="text-center"><?=\Yii::t('oscar', 'Sentinel sites')?></h2>
        </div>
        <?php } ?>
        <?php if($generator->getQuestionValue('ew3') == '-oth-') { ?>
        <div class="col-xs-4">
            <h4><?=\Yii::t('oscar', 'Reported timely')?></h4>
            <?=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('ew3[other]')])?>
        </div>
        <?php } ?>
        <?php if($generator->getQuestionValue('ew2') == '-oth-') { ?>
        <div class="col-xs-4">
            <h4><?=\Yii::t('oscar', 'Provided complete reports')?></h4>
            <?=$this->render('singlePercentageChart', ['percentage' => $generator->getQuestionValue('ew2[other]')])?>
        </div>
        <?php } ?>
    </div>
    <div class="row no-break">
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
                <div class="col-xs-4">
                    <h4>&nbsp;</h4>
                    <h1 class="text-center"><?=$value?></h1>
                    <h2 class="text-center"><?=$key?></h2>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <div class="row no-break">
        <h3 class="col-xs-12"><?=\Yii::t('oscar', 'Funding')?></h3>
        <div class="col-xs-4">
            <h4><?=\Yii::t('oscar', 'WHO funded')?></h4>
            <?= $this->render('singlePercentageChart', ['percentage' => $generator->getPercentage($generator->response, 'resmob2[rmwho_SQ002]', 'resmob2[rmwho_SQ001]')]) ?>
        </div>
        <div class="col-xs-4">
            <h4><?=\Yii::t('oscar', 'Health Sector funded')?></h4>
            <?= $this->render('singlePercentageChart', ['percentage' => $generator->getPercentage($generator->response, 'resmob2[rmhc_SQ002]', 'resmob2[rmhc_SQ001]')]) ?>
        </div>
        <div class="col-xs-4">
            <h4><?=\Yii::t('oscar', 'Overall funded')?></h4>
            <?= $this->render('singlePercentageChart', ['percentage' => round(($generator->getQuestionValue('resmob2[rmhc_SQ002]') + $generator->getQuestionValue('resmob2[rmwho_SQ002]')) * 100 / ($generator->getQuestionValue('resmob2[rmhc_SQ001]') + $generator->getQuestionValue('resmob2[rmwho_SQ001]')))]) ?>
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
                    <td><?=$formatter->asPercent($generator->getQuestionValue('resmob2[rmwho_SQ002]') / $generator->getQuestionValue('resmob2[rmwho_SQ001]'))?></td>
                </tr>
                <tr>
                    <td><?=\Yii::t('oscar', 'Health sector')?></td>
                    <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmhc_SQ001]'))?></td>
                    <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmhc_SQ002]'))?></td>
                    <td><?=$formatter->asPercent($generator->getQuestionValue('resmob2[rmhc_SQ002]') / $generator->getQuestionValue('resmob2[rmhc_SQ001]'))?></td>
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