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
                $workspace_url = \Yii::$app->user->can(Permission::PERMISSION_LIMESURVEY, Workspace::findOne(['id' => $response['workspace_id']])) ? Url::to(['/workspace/limesurvey', 'id' => $response['workspace_id']]) : Url::to(["/project/workspaces",'id' => $this->element->page->project->id, 'Workspace[id]' => $response['workspace_id']]);
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
                        'data' => $pointData
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
        $data = Json::encode($this->getCollections($this->data), JSON_PRETTY_PRINT);
        $title = Json::encode($this->getTitleFromCode($this->code));
        $types = Json::encode($this->getAnswers($this->code));
        $this->view->registerJs(<<<JS
        (function() {
            try {
                let rmax = 30, //Maximum radius for cluster pies
                markerclusters = L.markerClusterGroup({
                    maxClusterRadius: 2*rmax,
                    iconCreateFunction: defineClusterIcon 
                }), 
                map = L.map($id, $config);

                

                for (let baseLayer of $baseLayers) {
                    switch (baseLayer.type) {
                        case 'tileLayer':
                            L.tileLayer(baseLayer.url, baseLayer.options || {}).addTo(map);
                            break;
                    }
                }
                map.addLayer(markerclusters);

                let bounds = [];
                let data = $data;
                
                let layers = {};
                for (let set of data) {

                    var markers = L.geoJson(set.features, {
                        pointToLayer: defineFeature,
                        onEachFeature: defineFeaturePopup
                    });
                    markerclusters.addLayer(markers);
                    map.fitBounds(markers.getBounds());

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
                    
                    /*var popup = L.popup({'className' : "hf-popup"}).setContent("<div class='hf-summary'>"+
                            "<h2>"+set.features[0].properties.title+"</h2>" +
                            "<a href='"+set.features[0].properties.workspace_url+"' class='btn btn-primary'>"+set.features[0].properties.workspace_title+"</a>"+
                        "</div>");*/
                    layer.bindTooltip(function(e) {
                        return e.feature.properties.title;
                    });
                    let popup = layer.bindPopup(function(e) {
                        return "<div class='hf-summary'>"+
                            "<h2>"+e.feature.properties.title+"</h2>" +
                            "<a href='"+e.feature.properties.workspace_url+"' class='btn btn-primary'>"+e.feature.properties.workspace_title+"</a>"+
                        "</div>";
                    }, {'className' : "hf-popup"}).getPopup();
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

                let layerControl = L.control.layers([], layers, {
                    collapsed: false,
                });
                let parentAdd = layerControl.onAdd;
                layerControl.onAdd = function() { 
                    let result = parentAdd.apply(this, arguments);
                    $(result).prepend('<p style="font-size: 1.3em; font-weight: bold; margin: 0;">' + $title + '</p>');
                    return result;
                };
                
                layerControl.addTo(map);

                
                
                L.control.scale({
                    metric: true,
                    imperial: false
                }).addTo(map);
                L.control.zoom({
                    position: "bottomleft"
                }).addTo(map);
                try {
                    map.fitBounds(bounds, {
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

            function defineFeature(feature, latlng) {
                console.log(feature);
                var categoryVal = feature.properties.title,
                iconVal = feature.properties.data.MoSD3;
                var myClass = 'marker category-'+categoryVal+' icon-'+iconVal;
                var myIcon = L.divIcon({
                    className: myClass,
                    iconSize:null
                });
                return L.marker(latlng, {icon: myIcon});
            }

            function defineFeaturePopup(feature, layer) {
                console.log($types);
                var props = feature.properties,
                    fields = $types,
                    popupContent = '';
                    popupContent += '<span class="attribute"><span class="label">test:</span> value</span>';
                /*popupFields.map( function(key) {
                    if (props[key]) {
                    var val = props[key],
                        label = fields[key].name;
                    if (fields[key].lookup) {
                        val = fields[key].lookup[val];
                    }
                    popupContent += '<span class="attribute"><span class="label">'+label+':</span> '+val+'</span>';
                    }
                });*/
                popupContent = '<div class="map-popup">'+popupContent+'</div>';
                layer.bindPopup(popupContent,{offset: L.point(1,-2)});
            }

            function defineClusterIcon(cluster) {
                var children = cluster.getAllChildMarkers(),
                    n = children.length, //Get number of markers in cluster
                    strokeWidth = 1, //Set clusterpie stroke width
                    r = 30-2*strokeWidth-(n<10?12:n<100?8:n<1000?4:0), //Calculate clusterpie radius...
                    iconDim = (r+strokeWidth)*2, //...and divIcon dimensions (leaflet really want to know the size)
                    
                    
                    data = d3.nest() //Build a dataset for the pie chart
                    .key(function(d) { return d.feature.properties[categoryField]; })
                    .entries(children, d3.map),
                    //bake some svg markup
                    html = bakeThePie({data: data,
                                        valueFunc: function(d){return d.values.length;},
                                        strokeWidth: 1,
                                        outerRadius: r,
                                        innerRadius: r-10,
                                        pieClass: 'cluster-pie',
                                        pieLabel: n,
                                        pieLabelClass: 'marker-cluster-pie-label',
                                        pathClassFunc: function(d){return "category-"+d.data.key;},
                                        pathTitleFunc: function(d){return metadata.fields[categoryField].lookup[d.data.key]+' ('+d.data.values.length+' accident'+(d.data.values.length!=1?'s':'')+')';}
                                    }),
                    //Create a new divIcon and assign the svg markup to the html property
                    myIcon = new L.DivIcon({
                        html: html,
                        className: 'marker-cluster', 
                        iconSize: new L.Point(iconDim, iconDim)
                    });
                return myIcon;
            }
            function bakeThePie(options) {
                /*data and valueFunc are required*/
                if (!options.data || !options.valueFunc) {
                    return '';
                }
                var data = options.data,
                    valueFunc = options.valueFunc,
                    r = options.outerRadius?options.outerRadius:28, //Default outer radius = 28px
                    rInner = options.innerRadius?options.innerRadius:r-10, //Default inner radius = r-10
                    strokeWidth = options.strokeWidth?options.strokeWidth:1, //Default stroke is 1
                    pathClassFunc = options.pathClassFunc?options.pathClassFunc:function(){return '';}, //Class for each path
                    pathTitleFunc = options.pathTitleFunc?options.pathTitleFunc:function(){return '';}, //Title for each path
                    pieClass = options.pieClass?options.pieClass:'marker-cluster-pie', //Class for the whole pie
                    pieLabel = options.pieLabel?options.pieLabel:d3.sum(data,valueFunc), //Label for the whole pie
                    pieLabelClass = options.pieLabelClass?options.pieLabelClass:'marker-cluster-pie-label',//Class for the pie label
                    
                    origo = (r+strokeWidth), //Center coordinate
                    w = origo*2, //width and height of the svg element
                    h = w,
                    donut = d3.layout.pie(),
                    arc = d3.svg.arc().innerRadius(rInner).outerRadius(r);
                    
                //Create an svg element
                var svg = document.createElementNS(d3.ns.prefix.svg, 'svg');
                //Create the pie chart
                var vis = d3.select(svg)
                    .data([data])
                    .attr('class', pieClass)
                    .attr('width', w)
                    .attr('height', h);
                    
                var arcs = vis.selectAll('g.arc')
                    .data(donut.value(valueFunc))
                    .enter().append('svg:g')
                    .attr('class', 'arc')
                    .attr('transform', 'translate(' + origo + ',' + origo + ')');
                
                arcs.append('svg:path')
                    .attr('class', pathClassFunc)
                    .attr('stroke-width', strokeWidth)
                    .attr('d', arc)
                    .append('svg:title')
                    .text(pathTitleFunc);
                            
                vis.append('text')
                    .attr('x',origo)
                    .attr('y',origo)
                    .attr('class', pieLabelClass)
                    .attr('text-anchor', 'middle')
                    //.attr('dominant-baseline', 'central')
                    /*IE doesn't seem to support dominant-baseline, but setting dy to .3em does the trick*/
                    .attr('dy','.3em')
                    .text(pieLabel);
                //Return the svg-markup rather than the actual element
                return serializeXmlNode(svg);
            }



        })();

JS
        );

        return parent::run();
    }


    protected function registerClientScript()
    {
        $this->view->registerAssetBundle(MapBundle::class);
    }
}
