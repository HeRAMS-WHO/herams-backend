<?php
declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class SurveyJsCreatorBundle extends AssetBundle
{
    public $baseUrl = '@npm/survey-creator/';

    public $css = [
        'survey-creator.min.css'
    ];

    public $js = [
        'survey-creator.min.js'
    ];

    public $depends = [
        SurveyJsBundle::class,
    ];
}
