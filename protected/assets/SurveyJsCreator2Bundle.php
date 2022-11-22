<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class SurveyJsCreator2Bundle extends AssetBundle
{
    public $baseUrl = '@npm/survey-creator-knockout/';

    public $css = [
    ];

    public $js = [
        'survey-creator-knockout.min.js',
    ];

    public $depends = [
        SurveyModificationBundle::class,
        SurveyJsCreatorCoreBundle::class,
        SurveyJsKnockoutUiBundle::class,
        AceEditorBundle::class,
    ];
}
