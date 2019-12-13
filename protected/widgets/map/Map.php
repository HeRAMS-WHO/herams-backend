<?php


namespace prime\widgets\map;


use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

class Map extends Widget
{
    public const TILE_LAYER = 'tileLayer';
    public $baseLayers = [
        [
            "type" => self::TILE_LAYER,
            "url" => "https://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}",
            "url" => "https://services.arcgisonline.com/arcgis/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}",
            'options' => [
                'maxZoom' => 30,
                'maxNativeZoom' => 17
            ]
        ],
//        [
//            "type" => self::TILE_LAYER,
//            "url" => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
//        ]
    ];

    public $options = [
        'class' => [
            'map'
        ]
    ];

    public $center = [8.6753, 9.0820];
    public $zoom = 5.4;

    public $colors;

    public $data = [];

    public $markerRadius = 12.5;
    public function init()
    {
        $this->colors = $this->colors ?? new JsExpression('chroma.brewer.OrRd');
        parent::init();
    }
    public function run()
    {
        $this->registerClientScript();
        $options = $this->options;
        Html::addCssClass($options, strtr(__CLASS__, ['\\' => '_']));
        $options['id'] = $this->getId();
        echo Html::beginTag('div', $options);
        $id = Json::encode($this->getId());

        $config = Json::encode([
            'preferCanvas' => true,
            'center' => $this->center,
            'zoom' => $this->zoom,
            'zoomControl' => false,
            'maxZoom' => 18
        ]);

        $baseLayers = Json::encode($this->baseLayers);
        $data = Json::encode(array_values($this->data));

        $scale = Json::encode($this->colors);
        $this->view->registerJs(<<<JS
        (function() {
            try {
                let map = L.map($id, $config);
                window.map = map;
                for (let baseLayer of $baseLayers) {
                    switch (baseLayer.type) {
                        case 'tileLayer':
                            L.tileLayer(baseLayer.url, baseLayer.options || {}).addTo(map);
                            break;
                    }
                }
                // /*
                let bounds = [];
                let data = $data;
                let layers = {};
                let scale = chroma.scale($scale).colors(data.length);
                for (let set of data) {
                    let color = scale.pop();
                    let layer = L.geoJSON(set.features, {
                        pointToLayer: function(feature, latlng) {
                            bounds.push(latlng);
                            let marker = L.circleMarker(latlng, {
                                radius: {$this->markerRadius},
                                color: color,
                                weight: 2.5,
                                opacity: 1,
                                fillOpacity: 0.8
                            });
                            
                            let popup = marker.bindPopup(feature.properties.popup || feature.properties.title, {
                                maxWidth: "auto",
                                closeButton: false
                            });
                            popup.on('popupopen', function() {
                                let event = new Event('mapPopupOpen');
                                event.id = feature.properties.id;
                                window.dispatchEvent(event);
                            });
                            popup.on('popupclose', function() {
                                let event = new Event('mapPopupClose');
                                window.dispatchEvent(event);
                            });
                            
                            window.addEventListener('externalPopup', function(e) {
                                if (e.id == feature.properties.id) {
                                    marker.openPopup(popup);    
                                }
                                 
                            });
                            return marker;
                        }, 
                        onEachFeature: function(feature, layer) {
                        }
                    });
                    
                    let tooltip = layer.bindTooltip(function(e) {
                        return e.feature.properties.title;
                    });
                    // let popup = layer.bindPopup(function(e) {
                    //     console.log(arguments);
                    //     return e.feature.properties.popup || e.feature.properties.title;
                    // }, {
                    //     maxWidth: "auto",
                    //     closeButton: false
                    // });
                    
                    layer.addTo(map);
                    
                    let legend = document.createElement('span');
                    legend.classList.add('legend');
                    legend.style.setProperty('--color', color);
                    legend.title = set.features.length;
                    //legend.attributeStyleMap.set('--color', color);
                    legend.textContent = set.title;
                    
                    // legend.css
                    layers[legend.outerHTML] = layer;
                }
                if (layers.length > 0) {
                    L.control.layers([], layers, {
                        collapsed: false,
                        position: 'bottomright'
                    }).addTo(map);
                }
                
                
                L.control.zoom({
                    position: 'bottomright'
                }).addTo(map);
                L.control.scale({
                    metric: true,
                    imperial: false
                }).addTo(map);
                map.fitBounds(bounds, {
                    padding: [50, 50]
                });
            } catch(error) {
                console.error("Error in map widget JS", error);
            }
        })();

JS
        );

        echo Html::endTag('div');
    }


    protected function registerClientScript()
    {
        $this->view->registerAssetBundle(MapBundle::class);
    }

}