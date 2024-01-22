<?php
declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class IframeResizeBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/iframe-resizer/iframeResizer.min.js'
    ];
}
