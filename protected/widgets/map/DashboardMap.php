<?php


namespace prime\widgets\map;


use prime\objects\HeramsResponse;
use prime\traits\SurveyHelper;
use prime\widgets\element\Element;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\helpers\Json;
use yii\web\JsExpression;

class DashboardMap extends Element
{
    public const DEFAULT_MARKER_RADIUS = 2;
    use SurveyHelper;
    public const TILE_LAYER = 'tileLayer';
    public $baseLayers = [
        [
            "type" => DashboardMap::TILE_LAYER,
            "url" => "https://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}",
            'options' => [
                'maxZoom' => 30,
                'maxNativeZoom' => 17
            ]
        ]
    ];

    public $options = [
        'class' => ['map']
    ];

    private $center = [8.6753, 9.0820];
    private $zoom = 5.4;

    public $markerRadius = self::DEFAULT_MARKER_RADIUS;
    /**
     * @var HeramsResponse[]
     */
    public $data = [];
    /** @var SurveyInterface */
    public $survey;

    public $colors;

    public $code;

    private function getCollections(iterable $data)
    {
        try {
            $types = $this->getAnswers($this->code);
            $getter = function($response) {
                return $response->getValueForCode($this->code);
            };
        } catch (\InvalidArgumentException $e) {
            $types = [];
            $getter = function($response) {
                $getter = 'get' . ucfirst($this->code);
                return $response->$getter();
            };
        }

        $collections = [];
        /** @var HeramsResponse $response */
        foreach($data as $response) {
            $value = $getter($response);
            $latitude = $response->getLatitude();
            $longitude = $response->getLongitude();
            if (abs($latitude) < 0.0000001
                && abs($longitude) < 0.0000001) {
                continue;
            }

            if (!isset($collections[$value])) {
                $collections[$value] = [
                    "type" => "FeatureCollection",
                    'features' => [],
                    "title" => $types[$value] ?? $value ?? 'Unknown',
                    'color' => $this->colors[$value] ?? '#000000'
                ];
            }

            $point = [
                "type" => "Feature",
                "geometry" => [
                    "type" => "Point",
                    "coordinates" => [$longitude, $latitude]
                ],
                "properties" => [
                    'title' => $response->getName() ?? 'No name',
                ]

//                'subtitle' => '',
//                'items' => [
//                    'ownership',
//                    'building damage',
//                    'functionality'
//                ]
            ];
            $collections[$value]['features'][] = $point;
        }
        uksort($collections, function($a, $b) {
            if ($a === "" || $a === "-oth-") {
                return 1;
            } elseif ($b === "" || $b === "-oth-") {
                return -1;
            }
            return $a <=> $b;
        });
        return array_values($collections);
    }

    public function run()
    {
        $this->registerClientScript();
        $id = Json::encode($this->getId());

        $config = Json::encode([
            'preferCanvas' => true,
            'center' => $this->center,
            'zoom' => $this->zoom,
            'maxZoom' => 18
        ]);

        $baseLayers = Json::encode($this->baseLayers);
        $data = Json::encode($this->getCollections($this->data), JSON_PRETTY_PRINT);

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
            let bounds = [];
            let data = $data;
                let layers = {};
                for (let set of data) {
                    let layer = L.geoJSON(set.features, {
                        pointToLayer: function(feature, latlng) {
                            bounds.push(latlng);
                            return L.circleMarker(latlng, {
                                radius: $this->markerRadius,
                                color: set.color,
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
                    legend.style.setProperty('--color', set.color);
                    legend.title = set.features.length;
                    //legend.attributeStyleMap.set('--color', color);
                    legend.textContent = set.title;
                    
                    // legend.css
                    layers[legend.outerHTML] = layer;
                }
                L.control.layers([], layers, {
                    collapsed: false
                }).addTo(map);
                
                
                L.control.scale({
                    metric: true,
                    imperial: false
                }).addTo(map);
                try {
                    map.fitBounds(bounds, {
                        padding: [50, 50]
                    });
                } catch(err) {
                    console.error(err);
                }
        })();

JS
        );

        parent::run();
    }


    protected function registerClientScript()
    {
        $this->view->registerAssetBundle(MapBundle::class);
    }

}