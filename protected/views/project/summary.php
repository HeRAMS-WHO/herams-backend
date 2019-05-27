<?php

/** @var \prime\models\ar\Project $project */
/** @var \yii\web\View $this */

use prime\models\permissions\Permission;
use prime\widgets\chart\ChartBundle;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

$this->registerAssetBundle(ChartBundle::class);
$bundle = $this->registerAssetBundle(\prime\assets\IconBundle::class);
$font = $bundle->baseUrl . '/fonts/fonts/icomoon.woff';
//$this->registerLinkTag([
//    'rel' => 'preload',
//    'href' => $font,
//    'as' => 'font'
//], 'icomoon');

$this->title = $project->getDisplayField();
$hostInfo = \Yii::$app->request->hostInfo;

?>
<style>
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
        \prime\helpers\Icon::healthFacility(),
        Html::tag('b', $project->getFacilityCount()),
        \Yii::t('app', 'Health facilities')
    ])
?></div>
<div class="stat"><?=
    implode(" ", [
        \prime\helpers\Icon::contributors(),
        Html::tag('b', $project->getContributorCount()),
        \Yii::t('app', 'Contributors')
    ])
?></div>

<?php

    $types = $project->getTypeCounts();
    $total = array_sum($types);

    if ($total > 0) {
        echo Html::beginTag('div', ['class' => 'chart']);
        echo Html::tag('canvas', '', ['id' => 'chart1']);
        $url = $this->registerAssetBundle(\prime\assets\IconBundle::class)->baseUrl . '/svg/admin.svg';
        $typeCount = count($types);
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
                        'color' => $total > 0 ? '#eeeeee' : '#a5a5a5',
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
                    'display' => $total > 0,
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
        echo Html::endTag('div');
    }

    $functionality = $project->getFunctionalityCounts();
    $total = array_sum($functionality);
    if ($total > 0) {
        echo Html::beginTag('div', ['class' => 'chart']);
        echo Html::tag('canvas', '', ['id' => 'chart2']);

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
                        'borderWidth' => 0
                    ],
                    'center' => [
                        'sidePadding' => 40,
                        'color' => $total > 0 ? '#eeeeee' : '#a5a5a5',
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
                    'display' => $total > 0,
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
        echo Html::endTag('div');
    }


    $availability = $project->getSubjectAvailabilityCounts();
    $dataCount = count($availability);
    $total = array_sum($availability);
    if ($total > 0) {

        echo Html::beginTag('div', ['class' => 'chart']);
        echo Html::tag('canvas', '', ['id' => 'chart3']);

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
                        'borderWidth' => 0
                    ],
                    'center' => [
                        'sidePadding' => 40,
                        'color' => $total > 0 ? '#eeeeee' : '#a5a5a5',
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
        echo Html::endTag('div');
    }

    if (!empty($project->pages) && (true || \Yii::$app->user->can(Permission::PERMISSION_READ, $project))) {
       echo Html::a('Details', ['project/view', 'id' => $project->id], ['target' => '_top']);
    }

    ?>
</div>
<?php
if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}