<?php
declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class DashboardElementUiBundle extends AssetBundle
{
    public $sourcePath = __DIR__ . '/../../frontend/dashboard-element-ui/dist';

    /**
     * We don't include the actual JS, it is imported when needed.
     * @var \string[][]
     */
//    public $js = [
//        [
//            'dashboard-element-ui.es.js',
//            'type' => 'module'
//        ]
//    ];


    public function getImport(string $alias): string
    {
        $file = 'dashboard-element-ui';
        $url = json_encode("{$this->baseUrl}/{$file}.es.js");
        return  "import $alias from $url;";
    }
    public $css = [
        'style.css'
    ];
}
