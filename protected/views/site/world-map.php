<?php

declare(strict_types=1);

use herams\common\models\PermissionOld;
use prime\widgets\map\Map;
use prime\widgets\menu\SideMenu;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * @var \yii\data\DataProviderInterface $projects
 * @var \prime\components\View $this
 */

$this->title = "World overview";
$bundle = $this->registerAssetBundle(\prime\assets\IconBundle::class);
$font = $bundle->baseUrl . '/fonts/fonts/icomoon.woff';
$this->registerLinkTag([
    'rel' => 'preload',
    'href' => $font,
    'as' => 'font',
    'crossorigin' => 'anonymous',
], 'icomoon');

$this->params['body'] = [
    'class' => ['no-title', 'worldmap'],
];
// Order projects by status.
$collections = [];
foreach ([] as $project) {
    if (! isset($collections[$project->status])) {
        $collections[$project->status] = [
            "type" => "FeatureCollection",
            "title" => $project->statusText,
            "features" => [],
        ];
    }
    $collections[$project->status]["features"][] = [
        "type" => "Feature",
        "geometry" => [
            "type" => "Point",
            "coordinates" => [$project->longitude, $project->latitude],
        ],
        "properties" => [
            'id' => $project->id,
            'title' => $project->getDisplayField(),
            'url' => Url::to(
                [
                    '/api/project/summary',
                    'id' => $project->id,
                ]
            ),

        ],

    ];
}
$menu = SideMenu::begin([
    'collapsible' => true,
    'footer' => $this->render('//footer'),
]);

if (app()->user->can(PermissionOld::PERMISSION_CREATE_PROJECT)) {
    echo Html::a('New project', ['admin/project/create'], [
        'style' => [
            'color' => '#737373',
        ],
    ]);
}
SideMenu::end();
$config = Json::encode([
    'projectsUri' => Url::to(['/api/projects']),
    'sideParent' => $menu->getId(),
]);
$this->registerJs(<<<JS
    (async () => {
        const config = $config;
        // Get projects.
        const response = await fetch(config.projectsUri)
        const projects = await response.json()
        console.log(projects);
        // Render the list on the left.
        const parent = document.getElementById(config.sideParent).querySelector('nav')
        parent.prepend(...projects.map((project) => {
            const button = document.createElement('button')
            button.dataset.id = project.id
            button.type = 'button'
            button.textContent = project.name;
            return button
        }))
        
        
    })()
        
    
    window.addEventListener('click', function(e) {
        if (e.target.matches('.menu button[data-id]:not(.active)')) {
            const event = new Event('externalPopup');
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
]);
