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
    ];

    public $js = [
        '/js/surveyjs/FacilityTypeQuestion.js',
        '/js/surveyjs/ProjectVisibilityQuestion.js',
        '/js/surveyjs/LocalizableProjectTextQuestion.js',
        '/js/survey-modifications.js',
    ];

    public $css = [
        '/css/survey-modifications.css',
    ];
}
