<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class SurveyJsCreatorCoreBundle extends AssetBundle
{
    public $baseUrl = '@npm/survey-creator-core/';

    public $css = [
        'survey-creator-core.min.css',
    ];

    public $js = [
        'survey-creator-core.min.js',
        'survey-creator-core.i18n.min.js',
    ];

    public $depends = [
        SurveyModificationBundle::class,
        SurveyJsKnockoutUiBundle::class,
        AceEditorBundle::class,
    ];
}
