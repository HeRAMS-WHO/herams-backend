<?php

/** @var Project[] $projects */

use prime\models\ar\Project;
use yii\helpers\Html;
$this->title = "World overview";
// Order projects by status.
$collections = [];
foreach($projects as $project) {
    if (!isset($collections[$project->status])) {
        $collections[$project->status] = [
            "type" => "FeatureCollection",
            "title" => $project->statusText(),
            "features" => []
        ];
    }
    $collections[$project->status]["features"][] = [
        "type" => "Feature",
        "geometry" => [
            "type" => "Point",
            "coordinates" => [$project->latitude, $project->longitude]
        ],
        "properties" => [
            'id' => $project->id,
            'title' => $project->getDisplayField(),
            'popup' => Html::tag('iframe', '', [
                'src' => \yii\helpers\Url::to(['project/summary', 'id' => $project->id]),
            ])
        ]

    ];
}
echo Html::beginTag('div', ['class' => 'menu']);
echo Html::img("/img/HeRAMS.png");
echo Html::tag('hr');
echo Html::beginTag('nav');
foreach($projects as $project) {
    echo Html::button($project->getDisplayField(), [
        'data' => [
            'id' => $project->id
        ]
    ]);
}
echo Html::endTag('nav');
$this->registerJs(<<<JS
    window.addEventListener('click', function(e) {
        if (e.target.matches('.menu button')) {
            let event = new Event('externalPopup');
            event.id = e.target.getAttribute('data-id');
            window.dispatchEvent(event);
        }
    });
    window.addEventListener('mapPopupOpen', function(e) {
        console.log('mapPopupOpen')
        document.querySelectorAll('.menu button.active').forEach((e) => {
            e.classList.remove('active');
        });
        document.querySelector('.menu button[data-id="' + e.id + '"]').classList.add('active');
    });
    window.addEventListener('mapPopupClose', function(e) {
        console.log('close');
        document.querySelectorAll('.menu button.active').forEach((e) => {
            e.classList.remove('active');
        });
    })


JS
    , \yii\web\View::POS_END);

echo Html::beginTag('div', ['style' => [
    'position' =>'absolute',
    'bottom' => 0,
    'left' => 0,
    'height' => '150px',
    'right' => 0,
    'background-color' => 'var(--header-background-color)',
    'text-align' => 'center',
    'color' => 'white',

]]);
echo Html::tag('span', count($projects), [
    'style' => [
        'display' => 'block',
        'font-size' => '5em',
        'font-weight' => 'bold'

    ]
]);
echo Html::tag('span', 'HeRAMS projects');
echo Html::endTag('div');
echo Html::endTag('div');
echo \prime\widgets\map\Map::widget([
    'options' => [
        'class' => 'content',
        'style' => [
//            'position' => 'fixed',
//            'left' => 0,
//            'right' => 0,
//            'bottom' => YII_DEBUG ? '42px' : 0,
//            'top' => 0
        ]
    ],
    'data' => $collections
]);
echo Html::img("/img/HeRAMS.png", [
    'style' => [
        'position' => 'fixed',
        'padding' => '20px;',
        'left' => 0,
        'top' => 0,
        'width' => '150px',
        'background-color' => 'white'
    ]
]);
//echo UserMenu::widget([
//    'options' => [
//        'style' => [
//            'position' => 'fixed',
//            'right' => 0,
////            'background-color' => 'green'
//        ]
//    ]
//]);

?>
<style>
    body {
        font-family: "Source Sans Pro", sans-serif;
    }
    .leaflet-popup-content {
        margin: 0;
        background-image: url('/img/loader.svg');
        background-repeat: no-repeat;
        background-position: center;

    }
    .leaflet-popup-content-wrapper {
        overflow: hidden;
        padding: 0;

    }

    iframe {
        box-sizing: border-box;
        width: 400px;
        border-width: 0;
        height: 360px;
        overflow: hidden;
    }
</style>
