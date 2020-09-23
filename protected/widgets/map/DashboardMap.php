<?php


namespace prime\widgets\map;

use prime\interfaces\HeramsResponseInterface;
use prime\objects\HeramsSubject;
use prime\traits\SurveyHelper;
use prime\widgets\element\Element;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class DashboardMap extends Element
{
    use SurveyHelper;

    public const DEFAULT_MARKER_RADIUS = 2;
    public const TILE_LAYER = 'tileLayer';
    public $baseLayers = [
        [
            "type" => DashboardMap::TILE_LAYER,
            //            "url" => "https://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}",
            "url" => "https://services.arcgisonline.com/arcgis/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}",
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
     * @var HeramsResponseInterface[]
     */
    public $data = [];

    public $colors;

    public $code;

    public function setSurvey(SurveyInterface $survey): void
    {
        $this->survey = $survey;
    }

    private function getCollections(iterable $data)
    {
        $method  = 'get' . ucfirst($this->code);
        if (method_exists(HeramsResponseInterface::class, $method)) {
            $getter = function ($response) use ($method) {
                return $response->$method();
            };
        } else {
            /**
             * @param HeramsResponseInterface|HeramsSubject $response
             * @return mixed
             */
            $getter = function ($response) {
                return $response->getValueForCode($this->code);
            };
        }

        $types = $this->getAnswers($this->code);
        $collections = [];

        /** @var HeramsResponseInterface $response */
        foreach ($data as $response) {
            try {
                $value = $getter($response) ?? HeramsSubject::UNKNOWN_VALUE;
                $latitude = $response->getLatitude();
                $longitude = $response->getLongitude();
                $workspace_url = \Yii::$app->user->can(Permission::PERMISSION_LIMESURVEY, Workspace::findOne(['id' => $response['workspace_id']])) ? Url::to(['/workspace/limesurvey', 'id' => $response['workspace_id']]) : Url::to(["/project/workspaces", 'id' => $this->element->page->project->id, 'Workspace[id]' => $response['workspace_id']]);
                if (abs($latitude) < 0.0000001
                    || abs($longitude) < 0.0000001
                    || abs($latitude) > 90
                    || abs($longitude) > 180

                ) {
                    continue;
                }

                if (is_array($value)) {
                    $value = array_shift($value);
                }
                if (!isset($collections[$value])) {
                    $collections[$value] = [
                        "type" => "FeatureCollection",
                        'features' => [],
                        "title" => $types[$value] ?? $value ?? 'Unknown',
                        'value' => $value,
                        'color' => $this->colors[strtr($value, ['-' => '_'])] ?? '#000000'
                    ];
                }

                $pointData = [];
                foreach (['MoSD2', 'MoSD3', 'CONDB', 'HFFUNCT', 'HFACC'] as $key) {
                    $pointData[$key] = $response->getValueForCode($key);
                }
                $point = [
                    "type" => "Feature",
                    "geometry" => [
                        "type" => "Point",
                        "coordinates" => [$longitude, $latitude]
                    ],
                    "properties" => [
                        'title' => $response->getName() ?? 'No name',
                        'id' => $response->getId(),
                        'workspace_url' => $workspace_url,
                        'workspace_title' => \Yii::t('app', 'Workspaces'),
                        'data' => $pointData,
                        'color' => $collections[$value]['color']
                    ]

                    //                'subtitle' => '',
                    //                'items' => [
                    //                    'ownership',
                    //                    'building damage',
                    //                    'functionality'
                    //                ]
                ];
                $collections[$value]['features'][] = $point;
            } catch (\Throwable $t) {
                new \RuntimeException('An error occured while checking response: ' . $response->getId(), 0, $t);
            }
        }
        uksort($collections, function ($a, $b) {
            if ($a === "" || $a === "-oth-") {
                return 1;
            } elseif ($b === "" || $b === "-oth-") {
                return -1;
            }
            return $a <=> $b;
        });
        return array_values($collections);
    }

    public function run(): string
    {
        $this->registerClientScript();
        $id = Json::encode($this->getId());

        $config = Json::encode([
            'preferCanvas' => true,
            'center' => $this->center,
            'zoom' => $this->zoom,
            'zoomControl' => false,
            'maxZoom' => 18
        ]);

        $baseLayers = Json::encode($this->baseLayers);
        $collections = $this->getCollections($this->data);
        $data = Json::encode($collections, JSON_PRETTY_PRINT);
        $code = Json::encode($this->code);
        $title = Json::encode($this->getTitleFromCode($this->code));
        $this->view->registerJs(<<<JS
        (function() {
            try {
                let 
                map = L.map($id, $config),
                renderer = new DashboardMapRenderer(map);
                renderer.SetData($data, $baseLayers, $code);
                renderer.RenderMap();
                renderer.RenderLegend();
                
                
                /*var popup = L.popup({'className' : "hf-popup"}).setContent("<div class='hf-summary'>"+
                        "<h2>"+set.features[0].properties.title+"</h2>" +
                        "<a href='"+set.features[0].properties.workspace_url+"' class='btn btn-primary'>"+set.features[0].properties.workspace_title+"</a>"+
                    "</div>");
                
                
                layer.bindTooltip(function(e) {
                    return e.feature.properties.title;
                });
                let popup = layer.bindPopup(function(e) {
                    return "<div class='hf-summary'>"+
                        "<h2>"+e.feature.properties.title+"</h2>" +
                        "<a href='"+e.feature.properties.workspace_url+"' class='btn btn-primary'>"+e.feature.properties.workspace_title+"</a>"+
                    "</div>";
                }, {'className' : "hf-popup"}).getPopup();
                layer.addTo(map);*/
                
                /*let legend = document.createElement('span');
                legend.classList.add('legend');
                legend.style.setProperty('--color', data.color);
                legend.title = data.features.length;
                //legend.attributeStyleMap.set('--color', color);
                legend.textContent = data.title;
                
                // legend.css
                layers[legend.outerHTML] = layer;
                //}

                let layerControl = L.control.layers([], layers, {
                    collapsed: false,
                });
                let parentAdd = layerControl.onAdd;
                layerControl.onAdd = function() { 
                    let result = parentAdd.apply(this, arguments);
                    $(result).prepend('<p style="font-size: 1.3em; font-weight: bold; margin: 0;">' + $title + '</p>');
                    return result;
                };
                
                layerControl.addTo(map);*/

                
                
                L.control.scale({
                    metric: true,
                    imperial: false
                }).addTo(map);
                L.control.zoom({
                    position: "bottomleft"
                }).addTo(map);
                try {
                    map.fitBounds(renderer.bounds, {
                        padding: [20, 20]
                    });
                } catch(err) {
                    console.error(err);
                }
                
//                let title = L.control({
//                    position: "topleft"
//                });
//                title.onAdd = function (map) {
//                    this._div = L.DomUtil.create('div', 'info');
//                    this._div.innerHTML = '<div class="leaflet-bar" style="font-size: 12px; padding: 5px; font-weight: bold; color: #666; background-color: white;">' + $title + '</div>';
//                    return this._div;
//                };
//                
//                title.addTo(map);

            } catch (error) {
                console.error("Error in DashboardMap JS", error);
            } 

        })();

JS);

        return parent::run();
    }


    protected function registerClientScript()
    {
        $this->view->registerAssetBundle(MapBundle::class);
    }
}
