<?php

use prime\models\ar\Project;
use yii\helpers\Html;

/** @var Project $project */
$map = $project->getMap();
foreach($project->workspaces as $workspace) {
    echo Html::beginTag('div');
    echo Html::tag('h1', $workspace->title);
    echo Html::beginTag('table');

    foreach($workspace->getResponses() as $response) {
        try {
            new \prime\objects\HeramsResponse($response, $map);
        } catch (\Throwable $t) {
            echo Html::beginTag('tr');
            echo Html::tag('td', $response->id);
            echo Html::tag('td', $t->getMessage());
            echo Html::endTag('tr');
        }
    }
    echo Html::endTag('table');
    echo Html::endTag('div');
}