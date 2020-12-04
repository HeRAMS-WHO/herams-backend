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

    public $types = [];

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

        $collections = [];
        $titles = [];
        $answers = [];
        $variables = ['GEO1', 'MoSD3', 'CONDB', 'HFFUNCT', 'HFACC'];
        foreach ($variables as $key) {
            $titles[$key] = strtok(strip_tags($this->findQuestionByCode($key)->getText()), ':(');
            $answers[$key] = $this->getAnswers($key);
        }
        $ignored = 0;
        $total = 0;
        /** @var HeramsResponseInterface $response */
        foreach ($data as $response) {
            $total++;
            try {
                $value = $getter($response) ?? HeramsSubject::UNKNOWN_VALUE;
                $latitude = $response->getLatitude();
                $longitude = $response->getLongitude();
                if (abs($latitude) < 0.0000001
                    || abs($longitude) < 0.0000001
                    || abs($latitude) > 90
                    || abs($longitude) > 180

                ) {
                    $ignored ++;
                    continue;
                }

                if (is_array($value)) {
                    $value = array_shift($value);
                }
                if (!isset($collections[$value])) {
                    $collections[$value] = [
                        "type" => "FeatureCollection",
                        'features' => [],
                        "title" => $this->types[$value] ?? $value ?? 'Unknown',
                        'value' => $value,
                        'color' => $this->colors[strtr($value, ['-' => '_'])] ?? '#000000'
                    ];
                }

                $pointData = [];
                foreach ($variables as $key) {
                    $qtitle = $titles[$key];
                    $qvalue = $answers[$key][$response->getValueForCode($key)];
                    $pointData[] =  ["title" => "{$qtitle}", "value" => "{$qvalue}"];
                }
                $point = [
                    "type" => "Feature",
                    "geometry" => [
                        "type" => "Point",
                        "coordinates" => [$longitude, $latitude]
                    ],
                    "properties" => [
                        'title' => $response->getName() ?? 'No name',
                        'update' => date_format($response->getDate(), 'Y-m-d'),
                        'id' => $response->getId(),
                        'data' => $pointData,
                        'value' => $value,
                        'color' => $collections[$value]['color'],
                    ]
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
        echo $total.' (bad coordinates : '.$ignored.')';
        return array_values($collections);
    }

    public function run(): string
    {
        $this->registerClientScript();

        $mapConfig = Json::encode([
            'preferCanvas' => true,
            'center' => $this->center,
            'zoom' => $this->zoom,
            'zoomControl' => false,
            'maxZoom' => 15
        ]);

        $id = Json::encode($this->getId());
        $this->types = $this->getAnswers($this->code);
        $data =  Json::encode($this->getCollections($this->data));
        $baseLayers = Json::encode($this->baseLayers);
        $code = Json::encode($this->code);
        $markerRadius = Json::encode($this->markerRadius);
        $title = Json::encode($this->getTitleFromCode($this->code));
        $this->view->registerJs(<<<JS
        (function() {
            try {
                var rendererConfig = {
                    baseLayers: $baseLayers,
                    code: $code,
                    markerRadius: $markerRadius,
                    activatePopup: true,
                    clustered: true,
                    renderer: L.canvas()
                };
                
                let 
                map = L.map($id, $mapConfig),
                renderer = new DashboardMapRenderer(map, rendererConfig);
                renderer.SetData($data);
                renderer.RenderMap();
                renderer.RenderLegend($title);
                
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
        unset($mapConfig);
        unset($data);
        unset($this->data);
        unset($this->types);


        return parent::run();
    }


    protected function registerClientScript()
    {
        $this->view->registerAssetBundle(MapBundle::class);
    }
}
