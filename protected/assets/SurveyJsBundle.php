<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class SurveyJsBundle extends AssetBundle
{
    public $baseUrl = '@npm/survey-knockout/';

    public $css = [
        'modern.min.css',
    ];

    public $js = [
        'survey.ko.min.js',
    ];

    public $depends = [
        KnockoutBundle::class,
    ];
}
