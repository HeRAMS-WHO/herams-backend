<?php
declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

final class ChartJs3Bundle extends AssetBundle
{
    private const url = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js';
    public $js = [
        [
            self::url,
            'integrity' => 'sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==',

        ]
    ];

    public $jsOptions = [
        'crossorigin' => "anonymous"
    ];
}
