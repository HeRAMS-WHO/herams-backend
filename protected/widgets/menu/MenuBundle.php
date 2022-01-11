<?php
declare(strict_types=1);

namespace prime\widgets\menu;

use yii\web\AssetBundle;

class MenuBundle extends AssetBundle
{
    public $sourcePath = __DIR__ .'/assets';

    public $css = [
        'menu.css'
    ];
}
