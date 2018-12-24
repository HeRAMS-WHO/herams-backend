<?php


namespace prime\widgets\map;


use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class Map extends Widget
{
    public const TILE_LAYER = 'tileLayer';
    public $baseLayers = [];

    public $options = [];

    public $center = [8.6753, 9.0820];
    public $zoom = 5.4;

    public $data = [];


    public function init()
    {
        parent::init();
        $this->registerClientScript();

        $options = $this->options;
        Html::addCssClass($options, strtr(__CLASS__, ['\\' => '_']));
        $options['id'] = $this->getId();

        echo Html::beginTag('div', $options);

    }

    public function run()
    {

        $id = Json::encode($this->getId());

        $config = Json::encode([
            'preferCanvas' => true,
            'center' => $this->center,
            'zoom' => $this->zoom
        ]);

        $baseLayers = Json::encode($this->baseLayers);
        $data = Json::encode($this->data);

        $this->view->registerJs(<<<JS
        (function() {
            let map = L.map($id, $config);
            for (let baseLayer of $baseLayers) {
                switch (baseLayer.type) {
                    case 'tileLayer':
                        L.tileLayer(baseLayer.url, baseLayer.options || {}).addTo(map);
                        break;
                }
            }
            
            let data = $data;
                let layers = {};
                let scale = chroma.scale(chroma.brewer.OrRd).colors(data.length);
                for (let set of data) {
                    let color = scale.pop();
                    let layer = L.geoJSON(set.features, {
                        pointToLayer: function(feature, latlng) {
                            return L.circleMarker(latlng, {
                                radius: 2,
                                color: color,
                                weight: 1,
                                opacity: 1,
                                fillOpacity: 0.8
                            });
                        }
                    });
                    layer.bindTooltip(function(e) {
                        return e.feature.properties.title;
                    }),
                    layer.bindPopup(function(e) {
                        return e.feature.properties.title;
                    });
                    layer.addTo(map);
                    
                    let legend = document.createElement('span');
                    legend.classList.add('legend');
                    legend.style.setProperty('--color', color);
                    //legend.attributeStyleMap.set('--color', color);
                    legend.textContent = set.title;
                    
                    // legend.css
                    layers[legend.outerHTML] = layer;
                }
                L.control.layers([], layers, {
                    collapsed: false
                }).addTo(map);
        })();

JS
        );

        echo Html::endTag('div');
    }


    protected function registerClientScript()
    {
        $this->view->registerAssetBundle(MapBundle::class);
//        $config = [
//
//        ]
    }

}