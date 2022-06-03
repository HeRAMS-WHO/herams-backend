<?php
declare(strict_types=1);

namespace prime\assets;

use yii\web\AssetBundle;

class DashboardCardsBundle extends AssetBundle
{
    public $baseUrl = '@npm/@herams/dashboard-cards/dist';

    /**
     * We don't include the actual JS, it is imported when needed.
     * @var \string[][]
     */
    public function getImport(string $alias): string
    {
        $file = 'dashboard-cards';
        $url = json_encode("{$this->baseUrl}/{$file}.es.js");
        return  "import $alias from $url;";
    }
    public $css = [
        'style.css'
    ];
}
