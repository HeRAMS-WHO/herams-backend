<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class SurveyJsCreator2Bundle extends AssetBundle
{
    public $baseUrl = '@npm/survey-creator-knockout/';

    public $css = [
        'survey-creator-knockout.min.css'
    ];

    public $js = [
        'survey-creator-knockout.js'
    ];

    public $depends = [
        SurveyModificationBundle::class,
        SurveyJsKnockoutUiBundle::class,
        AceEditorBundle::class
    ];
}
