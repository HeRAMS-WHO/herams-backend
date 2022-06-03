<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class AceEditorBundle extends AssetBundle
{
    public $baseUrl = '@npm/ace-builds/src-min';

    public $js = [
        'ace.js',
        'worker-json.js',
        'mode-json.js',
        'ext-language_tools.js',
    ];

    public $css = [

    ];
}
