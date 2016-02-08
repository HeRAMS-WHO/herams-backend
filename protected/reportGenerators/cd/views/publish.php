<?php

/**
 * @var \yii\web\View $this
 */

//vdd($userData);

$report = $this->render('preview', [
    'userData' => $userData,
    'signature' => $signature,
    'project' => $project
]);

$report = str_replace('<textarea', '<div', $report);
$report = str_replace('</textarea>', '</div>', $report);

echo $report;