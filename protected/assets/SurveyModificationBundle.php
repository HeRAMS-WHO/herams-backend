<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class SurveyModificationBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $depends = [
        SurveyJsKnockoutUiBundle::class,
        SurveyJsCoreBundle::class
    ];

    public $js = [
        '/js/survey-modifications.js'
    ];
}
