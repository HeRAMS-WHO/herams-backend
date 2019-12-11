<?php

/** @var \prime\models\ar\Project $project */
/** @var \yii\web\View $this */

use prime\assets\IconBundle;
use prime\widgets\chart\ChartBundle;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

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
        --header-background-color: #33333b;
        --primary-button-background-color: #4a7bc7;
        --primary-button-hover-color: #3f86e6;
        --color: white;

    }

    body {
        margin: 0;
        background-color: transparent;
        color: var(--color);
        font-family: "Source Sans Pro", sans-serif;
        border-radius: 5px;
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
        border-radius: 5px;
        
    }


    h2 {
        margin: 60px 0 0;
        display: block;
        text-align: center;
        font-weight: 500;
        font-size: 22px;
        line-height: 22px;
        padding: 10px 0;
        text-transform: capitalize;
    }

    p {
        padding: 0 30px;
        line-height: 20px;
        font-size: 16px;
        text-align: justify;
        font-weight: 300;
    }


    a {
        grid-area: button;
        grid-column: span 6;
        background-color: var(--primary-button-background-color);
        font-weight: 400;
        text-align: center;
        font-size: 1rem;
        padding: 10px 0;
        border-radius: 0.25rem;
        text-decoration: none;
        color: inherit;
    }

    a:hover,
    a:visited,
    a:active {
        color: inherit;
        text-decoration: inherit;
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