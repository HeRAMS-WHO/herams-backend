<?php

declare(strict_types=1);

namespace prime\widgets\AgGrid;

use yii\web\AssetBundle;

class AgGridBundle extends AssetBundle
{
    public $baseUrl = '@npm/ag-grid-community/dist';

    public $js = [
        'ag-grid-community.js',
    ];

    public $css = [
        'styles/ag-grid.min.css',
    ];

    public $depends = [
        CustomRendererBundle::class
    ];
}
