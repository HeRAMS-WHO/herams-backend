<?php

use prime\models\ar\Project;
use yii\helpers\Html;

/** @var Project $project */
$map = $project->getMap();
foreach($project->workspaces as $workspace) {

    $rows = [];
    foreach($workspace->getResponses() as $response) {
        try {
            new \prime\objects\HeramsResponse($response, $map);
        } catch (\Throwable $t) {
            $rows[] = [
                $response->getId(),
                $t->getMessage(),
            ];
        }
    }
    echo Html::beginTag('div');
    echo Html::tag('h1', $workspace->title, [
        'style' => [
            'background-color' => count($rows) > 0 ? 'red' : 'green'
        ]
    ]);
    if (!empty($rows)) {
        echo Html::beginTag('table');
        foreach ($rows as $row) {
            echo Html::beginTag('tr');
            foreach($row as $cell) {
                echo Html::tag('td', $cell);
            }
            echo Html::endTag('tr');

        }
        echo Html::endTag('table');
    }
    echo Html::endTag('div');
}