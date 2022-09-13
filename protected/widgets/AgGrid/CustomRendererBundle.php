<?php
declare(strict_types=1);

namespace prime\widgets\AgGrid;

use yii\web\AssetBundle;

class CustomRendererBundle extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets/';

    public $js = [
        'ToggleButtonRenderer.js'
    ];

}
