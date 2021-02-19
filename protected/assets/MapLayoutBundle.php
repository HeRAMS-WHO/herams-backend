<?php
declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class MapLayoutBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/map.css',
    ];

    public $js = [
    ];

    public $depends = [
        NewAppAsset::class
    ];
}
