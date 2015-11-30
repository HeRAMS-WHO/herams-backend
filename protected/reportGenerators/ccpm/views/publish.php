<?php

/**
 * @var \yii\web\View $this
 */

//vdd($userData);

$report = $this->render('preview', [
    'userData' => $userData,
    'signature' => $signature,
    'responses' => $responses,
    'project' => $project
]);

foreach($userData->data as $key => $value) {
    $report = str_replace('<textarea name="' . $key . '"></textarea>', $value, $report);
}

echo $report;