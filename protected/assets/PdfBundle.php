<?php

namespace prime\assets;

use yii\web\AssetBundle;

class PdfBundle extends AssetBundle
{
    public $baseUrl = '/css';
    public $css = [
        'pdf.css'
    ];

    public $depends = [
        DashboardBundle::class
    ];
}
