<?php
use \yii\helpers\Html;
/**
 * @var \prime\reportGenerators\oscar\Generator $g
 * @var \yii\i18n\Formatter $f;
 */
// Begin block, this makes sure it is not rendered when all variables it uses are empty.
$g->beginBlock();
?>
    <style>
        .hcin-img-cont {
            padding-right: 0px;
        }

        .hcin-img {
            width: 100%;
            margin-top: 0.8em;
        }

        .hcin-title {
            margin-bottom: 0px;
            font-size: 0.9em;
        }
        table.hcin  td{
            padding-bottom: 0px;
            padding-top: 0px;
        }

        table.hcin td:first-child {
            text-align: right;

        }
    </style>
<div class="row">
    <h2 class="col-xs-12"><?=\Yii::t('oscar', 'Health Sector in numbers')?></h2>
    <div class="col-xs-12" style="border: 1px solid #666; padding-bottom: 1em;">
        <div class="row">
            <div class="col-xs-6">
                <div class="row">
                    <?=$this->render('healthSectorTable', [
                        'data' => [
                            \Yii::t('oscar', 'International NGOs') => ["hr1[1]", 'integer'],
                            \Yii::t('oscar', 'National NGOs') => ["hr1[2]", 'integer'],
                            \Yii::t('oscar', 'UN Agencies') => ["hr1[3]", 'integer'],
                            \Yii::t('oscar', 'National Authorities') => ["hr1[4]", 'integer'],
                            \Yii::t('oscar', 'Donors') => ["hr1[5]", 'integer'],
                            \Yii::t('oscar', 'Other') => ["hr1[6]", 'integer']
                        ],
                        'generator' => $g,
                        'formatter' => $f,
                        'title' => \Yii::t('oscar', 'Partners'),
                        'logo' => __DIR__ . ' /../assets/img/partners.jpg'
                    ]); ?>
                </div>
                <div class="row">
                    <?=$this->render('healthSectorTable', [
                        'data' => [
                            \Yii::t('oscar', 'Damaged / destroyed') => ["hi1", 'percent'],
                            \Yii::t('oscar', 'Functioning') => ["hi2", 'percent'],
                            \Yii::t('oscar', 'Supported by health partners') => ["hi3", 'percent']
                        ],
                        'generator' => $g,
                        'formatter' => $f,
                        'title' => \Yii::t('oscar', 'Health infrastructure'),
                        'logo' => __DIR__ . ' /../assets/img/health facilities.jpg'
                    ]); ?>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="row">
                    <?= $this->render('healthSectorTable', [ 'data' => [
                        \Yii::t('oscar', 'Sentinel sites') => ['ew1[other]', 'percent'],
                        \Yii::t('oscar', 'Reported timely') => ['ew3[other]', 'percent'],
                        \Yii::t('oscar', 'Provided complete reports') => ['ew2[other]', 'percent']
                    ],
                        'generator' => $g,
                        'formatter' => $f,
                        'title' => \Yii::t('oscar', 'EWARS'),
                        'logo' => __DIR__ . ' /../assets/img/ewarn.jpg'
                    ]); ?>
                </div>
                <div class="row">
                    <?=$this->render('healthSectorTable', ['data' => [
                        \Yii::t('oscar', 'Medicines delivered') => ['hri1[other]', 'integer'],
                        \Yii::t('oscar', 'Consultations provided') => ['hri2[other]', 'integer'],
                        \Yii::t('oscar', 'Surgical interventions') => ['hri3[other]', 'integer'],
                        \Yii::t('oscar', 'Patients referred') => ['hri4[other]', 'integer'],
                        \Yii::t('oscar', 'Births assisted') => ['hri5[other]', 'integer'],
                        \Yii::t('oscar', 'C-Sections') => ['hri6[other]', 'integer'],
                        \Yii::t('oscar', 'Measles vaccinations (<5 y.o)') => ['hri7a[other]', 'integer'],
                        \Yii::t('oscar', 'Measles vaccinations (<15 y.o)') => ['hri7b[other]', 'integer'],
                        \Yii::t('oscar', 'Polio vaccinations (<5 y.o)') => ['hri8a[other]', 'integer'],
                        \Yii::t('oscar', 'Polio vaccinations (<15 y.o)') => ['hri8b[other]', 'integer'],
                        \Yii::t('oscar', '3th doses of DTP (<1 y.o)') => ['hri9[other]', 'integer'],
                    ],
                        'generator' => $g,
                        'formatter' => $f,
                        'title' => \Yii::t('app', 'Health interventions'),
                        'logo' => __DIR__ . ' /../assets/img/health action.jpg'
                    ]); ?>
                </div>
                <div class="row">
                    <?=$this->render('healthSectorTable', [
                        'data' => [
                            \Yii::t('oscar', 'WHO Funded') => [$g->getPercentage('resmob2[rmwho_SQ002]', 'resmob2[rmwho_SQ001]'), 'calculatedPercent'],
                            \Yii::t('oscar', 'Health sector funded') => [$g->getPercentage('resmob2[rmhc_SQ002]', 'resmob2[rmhc_SQ001]'), 'calculatedPercent'],
                            \Yii::t('oscar', 'Overall funded') => [$g->getPercentage(['resmob2[rmhc_SQ002]', 'resmob2[rmwho_SQ002]'], ['resmob2[rmhc_SQ001]', 'resmob2[rmwho_SQ001]']), 'calculatedPercent']
                        ],
                        'generator' => $g,
                        'formatter' => $f,
                        'title' => \Yii::t('app', 'Funding'),
                        'logo' => __DIR__ . ' /../assets/img/funding.jpg'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$g->endBlock();