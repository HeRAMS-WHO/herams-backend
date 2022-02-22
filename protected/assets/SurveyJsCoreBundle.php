<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class SurveyJsCoreBundle extends AssetBundle
{
    public $baseUrl = '@npm/survey-core/';

    public $css = [
        'survey.min.css',
        'defaultV2.min.css',
    ];

    public $js = [
        'survey.core.js'
    ];

    public $depends = [
        KnockoutBundle::class,

    ];
}
