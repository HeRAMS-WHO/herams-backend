<?php

/** @var \prime\models\ar\Project $project */
/** @var \yii\web\View $this */

use prime\widgets\chart\ChartBundle;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
use prime\helpers\Icon;
$this->registerAssetBundle(ChartBundle::class);
$bundle = $this->registerAssetBundle(\prime\assets\IconBundle::class);
$font = $bundle->baseUrl . '/fonts/fonts/icomoon.woff';

$hostInfo = \Yii::$app->request->hostInfo;

$this->registerLinkTag([
    'rel' => 'preload',
    'href' => $font,
    'as' => 'font'
], 'icomoon');

$this->title = $project->getDisplayField();
?>
<style>
    @font-face {
        font-family: 'icomoon';
        src:  url('<?=$font ?>') format('woff');
        font-weight: normal;
        font-style: normal;
    }

    html {
        --header-background-color: #212529;
        --primary-button-background-color: #4177c1;
        --primary-button-hover-color: #3f86e6;
        --color: #eeeeee;

    }
    body {
        margin: 0;
        background-color: var(--background-color);
        color: var(--color);
        font-family: "Source Sans Pro", sans-serif;
    }

    .grid {
        display: grid;
        margin-top: 1em;
        margin-left: 10px;
        margin-right: 10px;
        grid-template-columns: repeat(6, 1fr);
        grid-template-rows: auto 200px auto;
        grid-template-areas:
            "stat stat stat stat stat stat"
            "chart chart chart chart chart chart"
            "button button button button button button"
    ;

        grid-row-gap: 1em;
        grid-column-gap: 1em;
    }

    h1 {
        margin: 0;
        text-transform: uppercase;
        background-color: var(--header-background-color);
        text-align: center;
        grid-area: title;
    }

    .stat {
        grid-area: stat;
        grid-column: span 3;
        text-align: center;
    }

    .chart {
        grid-area: chart;
        grid-column: span 2;
        height: 200px;
        overflow: hidden;
    }

    .chart canvas {
        height: 100%;
    }

    a {
        grid-area: button;
        grid-column: span 6;
        background-color: var(--primary-button-background-color);
        font-weight: 400;
        text-align: center;
        font-size: 1rem;
        padding-top: 10px;
        padding-bottom: 10px;
        border-radius: 0.25rem;
        text-decoration: none;
        color: inherit;
    }

    a:hover, a:visited, a:active {
        color: inherit;
        text-decoration: inherit;
    }






</style>
<h1><?= $this->title ?></h1>
<div class="grid">
<div class="stat"><?=
    implode(" ", [
        FAR::icon('hospital'),
        Html::tag('b', $project->getFacilityCount()),
        \Yii::t('app', 'Health facilities')
    ])
?></div>
<div class="stat"><?=
    implode(" ", [
        FAS::icon('users'),
        Html::tag('b', $project->getContributorCount()),
        \Yii::t('app', 'Contributors')
    ])
?></div>
<div class="chart">
    <canvas id="chart1"></canvas>
<?php


    $types = $project->getTypeCounts();
    $total = array_sum($types);

    $url = $this->registerAssetBundle(\prime\assets\IconBundle::class)->baseUrl . '/svg/admin.svg';
$typeCount = count($types);
    if ($total > 0) {
        $jsonConfig = \yii\helpers\Json::encode([
            'type' => 'doughnut',
            'data' => [
                'datasets' => [
                    [
                        'data' => array_values($types),
                        'backgroundColor' => new \yii\web\JsExpression("chroma.scale(['blue', 'white']).colors($typeCount)"),
                        'label' => 'Types'
                    ]
                ],
                'labels' => array_map(function ($key) use ($types, $total) {
                    $count = $types[$key];
                    $percentage = 100 * $count / $total;
                    return number_format($percentage, 1) . '% ' . $key;
                }, array_keys($types))
            ],
            'options' => [
                'elements' => [
                    'arc' => [
                        'borderWidth' => 0
                    ],
                    'center' => [
                        'sidePadding' => 40,
                        'color' => '#eeeeee',
                        'fontWeight' => "normal",
                        'fontStyle' => "icomoon",
                        // Facility
                        'text' => "\u{e90b}"
                    ]
                ],
                'cutoutPercentage' => 95,
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12,
                        'fontSize' => 12,
                    ]
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Type'
                ],
                'animation' => [
                    'animateScale' => true,
                    'animateRotate' => true
                ]
            ]
        ], JSON_PRETTY_PRINT);
        $js = <<<JS
    (function() {
        var config = {$jsonConfig};
        let canvas = document.getElementById('chart1').getContext('2d');
        let chart = new Chart(canvas, config);
    })();
JS;
        $this->registerJs($js);
    }
    ?>

    </div>
<div class="chart">
    <canvas id="chart2">
    </canvas>
    <?php
    $functionality = $project->getFunctionalityCounts();
    $total = array_sum($functionality);
    $dataCount = count($functionality);
    $jsonConfig = \yii\helpers\Json::encode([
        'type' => 'doughnut',
        'data' => [
            'datasets' => [
                [
                    'data' => array_values($functionality),
                    'backgroundColor' => new \yii\web\JsExpression("chroma.scale(['green', 'orange', 'red']).colors($dataCount)"),
                    'label' => 'Types'
                ]
            ],
            'labels' => array_map(function ($key) use ($functionality, $total) {
                $count = $functionality[$key];
                $percentage = 100 * $count / $total;
                return number_format($percentage, 1) . '% ' . $key;
            }, array_keys($functionality))
        ],
        'options' => [
            'elements' => [
                'arc' => [
                    'borderWidth' =>  0
                ],
                'center' => [
                    'sidePadding' => 40,
                    'color' => '#eeeeee',
                    'fontWeight' => "normal",
                    'fontStyle' => "icomoon",
                    // Functionality
                    'text' => "\u{e90a}"
                ]
            ],
            'cutoutPercentage' => 95,
            'responsive' => true,
            'maintainAspectRatio' => false,
            'legend' => [
                'position' => 'bottom',
                'labels' => [
                    'boxWidth' => 12,
                    'fontSize' => 12,
                ]
            ],
            'title' => [
                'display' => true,
                'text' => 'Functionality'
            ],
            'animation' => [
                'animateScale' => true,
                'animateRotate' => true
            ]
        ]
    ], JSON_PRETTY_PRINT);
    $js = <<<JS
    (function() {
        let canvas = document.getElementById('chart2').getContext('2d');
        let config = $jsonConfig;
        let chart = new Chart(canvas, config);
    })();
JS;
    $this->registerJs($js);
    ?>
</div>
<div class="chart">
    <canvas id="chart3">
        <?php
//        $svg = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(\Yii::getAlias('@app/assets/svg/availability.svg')));

        $availability = $project->getSubjectAvailabilityCounts();
        $dataCount = count($availability);
        $jsonConfig = \yii\helpers\Json::encode([
            'type' => 'doughnut',
            'data' => [
                'datasets' => [
                    [
                        'data' => array_values($availability),
                        'backgroundColor' => new \yii\web\JsExpression("chroma.scale(['green', 'orange', 'red', ]).colors($dataCount)"),
                        'label' => 'Types'
                    ]
                ],
                'labels' => array_keys($availability)
            ],
            'options' => [
                'elements' => [
                    'arc' => [
                        'borderWidth' =>  0
                    ],
                    'center' => [
                        'sidePadding' => 40,
                        'color' => array_sum($availability) > 0 ? '#eeeeee' : '#a5a5a5',
                        'fontWeight' => "normal",
                        'fontStyle' => "icomoon",
                        // Availability
                        'text' => "\u{e901}"
                    ]
                ],
                'cutoutPercentage' => 95,
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'display' => array_sum($availability) > 0,
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12,
                        'fontSize' => 12,
                    ]
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Service availability'
                ],
                'animation' => [
                    'animateScale' => true,
                    'animateRotate' => true
                ]
            ]
        ], JSON_PRETTY_PRINT);
        $js = <<<JS
    (function() {
        let canvas = document.getElementById('chart3').getContext('2d');
        let chart = new Chart(canvas, $jsonConfig);
    })();
JS;
        $this->registerJs($js);
        ?>
    </canvas>
</div>
    <?php
if (!empty($project->pages)) {
   echo Html::a('Details', ['project/view', 'id' => $project->id], ['target' => '_top']);
}

    ?>
</div>
<?php
if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}