<?php

/** @var \prime\models\ar\Project $project */
/** @var \yii\web\View $this */

use prime\models\permissions\Permission;
use prime\widgets\chart\ChartBundle;
use yii\helpers\Html;
use prime\helpers\Icon;

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
        --header-background-color: #33333b;
        --primary-button-background-color: #4a7bc7;
        --primary-button-hover-color: #3f86e6;
        --color: #ffffff;
    }
    body {
        margin: 0;
        background-color: transparent;
        color: var(--color);
        font-family: "Source Sans Pro", sans-serif;
    }

    .grid {
        display: grid;
        margin-top: 1em;
        margin-left: 20px;
        margin-right: 20px;
        grid-template-columns: repeat(6, 1fr);
        grid-template-rows: auto auto 200px auto;
        grid-template-areas:
            "stat stat stat stat stat stat"
            "line line line line line line"
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
        font-weight: 500;
        color: var(--color);
        font-size: 24px;
        line-height: 24px;
        padding: 7px 0;
    }

    .stat {
        grid-area: stat;
        grid-column: span 3;
        text-align: center;
        font-weight: 300;
        border-left: 1px solid #6a696e;
    }

    .stat:first-child {
        border-left: none !important;
    }
    .stat svg {
        margin-right: 5px;
    }

    .stat b {
        margin-right: 5px;
        font-size: 19px;
        line-height: 15px;
    }

    hr {
        grid-area: line;
        grid-column: span 6;
        width: 100%;
        height: 1px;
        border: none;
        background: #6a696e;
        margin: 0;
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

    .actions {
        grid-area: button;
        grid-column: span 6;
    }
    a {
        background-color: var(--primary-button-background-color);
        font-weight: 400;
        text-align: center;
        font-size: 1rem;
        padding: 8px 10px;
        border-radius: 5px;
        text-decoration: none;
        color: inherit;
        width: 30%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    a:hover {
        background-color: var(--primary-button-hover-color);
        transition: background 0.2s;
    }
    a:first-child {
        margin-right: 2%;
        width: 58%;
        
    }

    a:hover, a:visited, a:active {
        color: inherit;
        text-decoration: inherit;
    }

    a svg {
        margin-right: 5px;

    }




</style>
<h1><?= $this->title ?></h1>
<div class="grid">
<div class="stat">
    <?=
    implode(" ", [
        \prime\helpers\Icon::healthFacility(),
        Html::tag('b', $project->facilityCount),
        \Yii::t('app', 'Health facilities')
    ])
?>
</div>
<div class="stat">
    <?=
    implode(" ", [
        \prime\helpers\Icon::contributors(),
        Html::tag('b', $project->contributorCount),
        \Yii::t('app', 'Contributors')
    ])
?>
</div>
<hr/>
<?php
    $types = $project->getTypeCounts();
    $total = array_sum($types);
    if ($total > 0) {
        \Yii::beginProfile('chart1');
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
                'tooltips' => [
                        'enabled' => false,
                ],
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
        \Yii::endProfile('chart1');
    }

    $functionality = $project->getFunctionalityCounts();
    $total = array_sum($functionality);
    if ($total > 0) {
        \Yii::beginProfile('chart2');
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
                'tooltips' => [
                    'enabled' => false,
                ],
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
        \Yii::endProfile('chart2');
    }


    $availability = $project->getSubjectAvailabilityCounts();
    $dataCount = count($availability);
    $total = array_sum($availability);
    if ($total > 0) {
        \Yii::beginProfile('chart3');
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
                'tooltips' => [
                    'enabled' => false,
                ],
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
        \Yii::endProfile('chart3');
    }

    if (!empty($project->pages) && (true || \Yii::$app->user->can(Permission::PERMISSION_READ, $project))) {
        echo Html::beginTag('div', ['class' => 'actions']);
        echo Html::a(Icon::project().'Dashboard', ['project/view', 'id' => $project->id], ['target' => '_top']);
        echo Html::a(Icon::list().'Workspaces', ['project/workspaces', 'id' => $project->id], ['target' => '_top']);
        echo Html::endTag('div');
    }

    ?>
</div>
<?php
if (class_exists(\yii\debug\Module::class)) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}