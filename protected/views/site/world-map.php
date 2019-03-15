<?php

/** @var Project[] $projects */

use prime\helpers\Icon;
use prime\models\ar\Project;
use prime\widgets\map\Map;
use yii\helpers\Html;
$this->title = "World overview";

$this->params['body'] = [
    'class' => ['no-title']
];
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
            "coordinates" => [$project->longitude, $project->latitude]
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
        if (e.target.matches('.menu button:not(.active)')) {
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

echo Html::beginTag('div',[
    'class' => 'footer'
]);
echo Icon::mapMarkedAlt(['class' => 'subject']);
echo Html::tag('div', count($projects), [
    'class' => 'counter'
]);
echo Html::tag('div', 'HeRAMS projects', [
    'class' => 'subject'
]);
echo Html::beginTag('div', ['class' => 'status']);

echo Icon::sync();

echo Html::endTag('div');

echo Html::a(Icon::chevronLeft(), '#', ['class' => 'left']);
echo Html::a(Icon::chevronRight(), '#', ['class' => 'right']);

echo Html::endTag('div');
echo Html::endTag('div');
echo Map::widget([
    'colors' => ["#4075c3"],
    'options' => [
        'class' => 'content',
    ],
    'data' => $collections
]);

?>
<style>
    .leaflet-popup-content {
        margin: 0;
        /*background-image: url('/img/loader.svg');*/
        /*background-repeat: no-repeat;*/
        /*background-position: center;*/
        background-color: #42424b;

        padding-bottom: 10px;

    }
    .leaflet-popup-content-wrapper {
        overflow: hidden;
        padding: 0;

    }

    iframe {
        box-sizing: border-box;
        max-width: 400px;
        border-width: 0;
        min-width: 200px;
        min-height: 370px;
        overflow: hidden;
    }
</style>
