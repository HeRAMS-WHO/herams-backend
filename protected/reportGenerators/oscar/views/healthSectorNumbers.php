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
                    <?=$this->render('healthSectorTable', ['data' => [
                        \Yii::t('oscar', 'International NGOs') => $f->asInteger($g->getQuestionValue("hr1[1]")),
                        \Yii::t('oscar', 'National NGOs') => $f->asInteger($g->getQuestionValue("hr1[2]")),
                        \Yii::t('oscar', 'UN Agencies') => $f->asInteger($g->getQuestionValue("hr1[3]")),
                        \Yii::t('oscar', 'National Authorities') => $f->asInteger($g->getQuestionValue("hr1[4]")),
                        \Yii::t('oscar', 'Donors') => $f->asInteger($g->getQuestionValue("hr1[5]")),
                        \Yii::t('oscar', 'Other') => $f->asInteger($g->getQuestionValue("hr1[6]"))
                    ],
                        'generator' => $g,
                        'title' => \Yii::t('oscar', 'Partners'),
                        'logo' => __DIR__ . ' /../assets/img/partners.jpg'
                    ]); ?>
                </div>
                <div class="row">
                    <?=$this->render('healthSectorTable', [ 'data' => [
                            \Yii::t('oscar', 'Damaged / destroyed') => $f->asPercent($g->getQuestionValue("hi1")),
                            \Yii::t('oscar', 'Functioning') => $f->asPercent($g->getQuestionValue("hi2")),
                            \Yii::t('oscar', 'Supported by health partners') => $f->asPercent($g->getQuestionValue("hi3"))
                    ],
                        'generator' => $g,
                        'title' => \Yii::t('oscar', 'Health infrastructure'),
                        'logo' => __DIR__ . ' /../assets/img/health facilities.jpg'
                    ]); ?>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="row">
                    <?= $this->render('healthSectorTable', [ 'data' => [
                        \Yii::t('oscar', 'Sentinel sites') => $f->asPercent($g->getQuestionValue('ew1[other]')),
                        \Yii::t('oscar', 'Reported timely') => $f->asPercent($g->getQuestionValue('ew3[other]')),
                        \Yii::t('oscar', 'Provided complete reports') => $f->asPercent($g->getQuestionValue('ew2[other]'))
                    ],
                        'generator' => $g,
                        'title' => \Yii::t('oscar', 'EWARS'),
                        'logo' => __DIR__ . ' /../assets/img/ewarn.jpg'
                    ]); ?>
                </div>
                <div class="row">
                    <?=$this->render('healthSectorTable', ['data' => [
                        \Yii::t('oscar', 'Medicines delivered') => $f->asInteger($g->getQuestionValue('hri1[other]')),
                        \Yii::t('oscar', 'Consultations provided') => $f->asInteger($g->getQuestionValue('hri2[other]')),
                        \Yii::t('oscar', 'Surgical interventions') => $f->asInteger($g->getQuestionValue('hri3[other]')),
                        \Yii::t('oscar', 'Patients referred') => $f->asInteger($g->getQuestionValue('hri4[other]')),
                        \Yii::t('oscar', 'Births assisted') => $f->asInteger($g->getQuestionValue('hri5[other]')),
                        \Yii::t('oscar', 'C-Sections') => $f->asInteger($g->getQuestionValue('hri6[other]')),
                        \Yii::t('oscar', 'Measles vaccinations (<5 y.o)') => $f->asInteger($g->getQuestionValue('hri7a[other]')),
                        \Yii::t('oscar', 'Measles vaccinations (<15 y.o)') => $f->asInteger($g->getQuestionValue('hri7b[other]')),
                        \Yii::t('oscar', 'Polio vaccinations (<5 y.o)') => $f->asInteger($g->getQuestionValue('hri8a[other]')),
                        \Yii::t('oscar', 'Polio vaccinations (<15 y.o)') => $f->asInteger($g->getQuestionValue('hri8b[other]')),
                        \Yii::t('oscar', '3th doses of DTP (<1 y.o)') => $f->asInteger($g->getQuestionValue('hri9[other]')),
                    ],
                        'generator' => $g,
                        'title' => \Yii::t('app', 'Health interventions'),
                        'logo' => __DIR__ . ' /../assets/img/health action.jpg'
                    ]); ?>
                </div>
                <div class="row">
                    <?=$this->render('healthSectorTable', [
                        'data' => [
                            \Yii::t('oscar', 'WHO Funded') => $f->asPercent($g->getPercentage('resmob2[rmwho_SQ002]', 'resmob2[rmwho_SQ001]')),
                            \Yii::t('oscar', 'Health sector funded') => $f->asPercent($g->getPercentage('resmob2[rmhc_SQ002]', 'resmob2[rmhc_SQ001]')),
                            \Yii::t('oscar', 'Overall funded') => $f->asPercent($g->getPercentage(['resmob2[rmhc_SQ002]', 'resmob2[rmwho_SQ002]'], ['resmob2[rmhc_SQ001]', 'resmob2[rmwho_SQ001]']))
                        ],
                        'generator' => $g,
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