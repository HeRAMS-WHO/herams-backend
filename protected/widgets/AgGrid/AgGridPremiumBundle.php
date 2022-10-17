<?php

declare(strict_types=1);

namespace prime\widgets\AgGrid;

use yii\web\AssetBundle;

class AgGridPremiumBundle extends AssetBundle
{
    public $baseUrl = '@npm/ag-grid-enterprise/dist';

    public $js = [
        'ag-grid-enterprise.js',
    ];

    public $css = [
        'styles/ag-grid.min.css',
    ];

    public $depends = [
        CustomRendererBundle::class,
    ];
}
