<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class SurveyJsKnockoutUiBundle extends AssetBundle
{
    public $baseUrl = '@npm/survey-knockout-ui/';

    public $js = [
        'survey-knockout-ui.min.js',
    ];

    public $depends = [
        SurveyJsCoreBundle::class,
    ];
}
