<?php

/** @var \yii\data\DataProviderInterface $projects */

use prime\models\permissions\Permission;
use prime\widgets\map\Map;
use prime\widgets\menu\SideMenu;
use yii\helpers\Html;

$this->title = "World overview";
$bundle = $this->registerAssetBundle(\prime\assets\IconBundle::class);
$font = $bundle->baseUrl . '/fonts/fonts/icomoon.woff';
$this->registerLinkTag([
    'rel' => 'preload',
    'href' => $font,
    'as' => 'font'
], 'icomoon');

$this->params['body'] = [
    'class' => ['no-title']
];
// Order projects by status.
$collections = [];
foreach($projects->getModels() as $project) {
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
SideMenu::begin([
    'collapsible' => true,
    'footer' => $this->render('//footer')
]);

/** @var \prime\models\ar\Project $project */
foreach($projects->getModels() as $project) {
    echo Html::button($project->getDisplayField(), [
        'data' => [
            'id' => $project->id,
            'facilityCount' => $project->facilityCount,
            'contributorCount' => $project->contributorCount,
            'status' => $project->status,
            'typeCounts' => $project->getTypeCounts(),
            'functionalityCounts' => $project->getFunctionalityCounts(),
            'availabilityCounts' => $project->getSubjectAvailabilityCounts()


        ]
    ]);

}
if (app()->user->can(Permission::PERMISSION_ADMIN)) {
    echo Html::a('New project', ['project/create'], [
        'style' => [
            'color' => '#737373'
        ]
    ]);
}
SideMenu::end();
$this->registerJs(<<<JS
    window.addEventListener('click', function(e) {
        if (e.target.matches('.menu button[data-id]:not(.active)')) {
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
        max-width: 500px;
        border-width: 0;
        width: 400px;
        min-width: 300px;
        min-height: 350px;
        overflow: hidden;
    }
</style>
