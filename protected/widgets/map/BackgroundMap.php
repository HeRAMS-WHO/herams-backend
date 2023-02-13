<?php

declare(strict_types=1);

namespace prime\widgets\map;

use prime\widgets\chart\ChartBundle;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Similar to map but without any elements; this is just a static background rendered dynamically
 */
final class BackgroundMap extends Widget
{
    private const TILE_LAYER = 'tileLayer';

    private array $baseLayers = [
        [
            "type" => self::TILE_LAYER,
            "url" => "https://services.arcgisonline.com/arcgis/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}",
            'options' => [
                'maxZoom' => 30,
                'maxNativeZoom' => 17,
            ],
        ],
    ];

    private array $options = [
        'class' => [
            'map',
        ],
    ];

    public function run()
    {
        $this->registerClientScript();
        $options = $this->options;
        Html::addCssClass($options, strtr(__CLASS__, [
            '\\' => '_',
        ]));
        $options['id'] = $this->getId();
        $id = Json::encode($this->getId());
        $config = Json::encode([
            'preferCanvas' => true,
            'center' => [8.6753, 9.0820],
            'zoom' => 3,
            'zoomControl' => false,
            'maxZoom' => 3,
            'minZoom' => 3,
        ]);

        $baseLayers = Json::encode($this->baseLayers);

        $this->view->registerJs(<<<JS
        (function() {
            try {
                const map = L.map($id, $config);
                window.map = map;
                for (const baseLayer of $baseLayers) {
                    switch (baseLayer.type) {
                        case 'tileLayer':
                            L.tileLayer(baseLayer.url, baseLayer.options || {}).addTo(map);
                            break;
                    }
                }
                // Disable all interaction
                map._handlers.forEach(function(handler) {
                    handler.disable();
                });
                
                
            } catch(error) {
                console.error("Error in map widget JS", error);
            }
            
        })();

JS);

        return Html::tag('div', '', $options);
    }

    protected function registerClientScript()
    {
        $this->view->registerAssetBundle(ChartBundle::class);
        $this->view->registerAssetBundle(MapBundle::class);
    }
}
