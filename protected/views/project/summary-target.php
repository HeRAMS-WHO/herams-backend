<?php

/** @var \prime\models\ar\Project $project */
/** @var \yii\web\View $this */

use prime\assets\IconBundle;

$bundle = $this->registerAssetBundle(IconBundle::class);
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

    h1 {
        margin: 0;
        text-transform: uppercase;
        background-color: var(--header-background-color);
        text-align: center;
    }

    h2 {
        background-color: var(--header-background-color);
        text-align: center;
    }

    p {
        margin-left: 30px;
        margin-right: 30px;
        line-height: 1.5em;
        text-align: justify;
    }



</style>
<h1><?= $this->title ?></h1>
<h2><?= \Yii::t('app', 'In progress') ?></h2>
<p>
    This project is in the process of being set up. When it becomes active this popup will show key metrics and allow access to the project dashboard.
</p>
<?php
if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}