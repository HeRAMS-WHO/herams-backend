<?php
declare(strict_types=1);

namespace prime\assets;


use yii\web\AssetBundle;

class FormBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/form.css'
    ];

    public $js = [
    ];
}