<?php

declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class IframeResizerContentWindowBundle extends AssetBundle
{
    public $baseUrl = '@npm/iframe-resizer/js';

    public $js = [
        'iframeResizer.contentWindow.min.js'
    ];
}
