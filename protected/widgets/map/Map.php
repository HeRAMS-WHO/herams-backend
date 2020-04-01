<?php


namespace prime\widgets\map;

use http\QueryString;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use prime\widgets\chart\ChartBundle;

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

    /**
     * This is the popup content that will be shown while the data is being fetched.
     * @return string
     */
    private function renderPopupLoader(): string
    {
        return <<<HTML
        <div class="loader-wrapper">
            <h1>Loading project summary</h1>
            <p>We're getting your summary ready...</p>
            <div class="loader-anim" style="background-image: url('/img/loader.svg');"></div>
        </div>
HTML;
    }

    public function run()
    {
        $this->registerClientScript();
        $options = $this->options;
        Html::addCssClass($options, strtr(__CLASS__, ['\\' => '_']));
        $options['id'] = $this->getId();
        echo Html::beginTag('div', $options);
        echo Html::tag('template', $this->renderPopupLoader());

        $id = Json::encode($this->getId());

        $config = Json::encode([
            'preferCanvas' => true,
            'center' => $this->center,
            'zoom' => $this->zoom,
            'zoomControl' => false,
            'maxZoom' => 16,
            'minZoom' => 3
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
                            
                            
                            let popup = marker.bindPopup((layer => document.querySelector("#" + {$id} + " template").content.cloneNode(true)), {
                                maxWidth: "auto",
                                closeButton: false
                            }).getPopup();

                            let fetched = false;
                            // On the first open fetch remote content
                            marker.on('popupopen', function() {
                                if (fetched) {
                                    loadChartsForCountry(feature.json);
                                    return;
                                }
                                fetch(feature.properties.url)
                                    .then((r) => r.json())
                                    .then((json) => {
                                        feature.json = json;
                                        
                                        popup.setContent(
                                        '<div class="project-summary">' + 
                                        '<h1>' + json.title + '</h1>' +
                                        '<div class="grid">' +
                                            '<div class="stat"><strong>' +
                                                json.facilityCount +
                                            '</strong> Health facilities</div>' +
                                            '<div class="stat"><strong>' +
                                                json.contributorCount +
                                            '</strong> Contributors</div>' +
                                            '<hr/>' +
                                            //'<div class="chart">'+ JSON.stringify(json, null, 2) +'</div>' +
                                            '<div class="chart"><div class="container-chart"><canvas id="chart1"></div>' +
                                            '<div id="js-legend-1" class="legend"></div></div>' +
                                            '<div class="chart"><div class="container-chart"><canvas id="chart2"></div>' +
                                            '<div id="js-legend-2" class="legend"></div></div>' +
                                            '<div class="chart"><div class="container-chart"><canvas id="chart3"></div>' +
                                            '<div id="js-legend-3" class="legend"></div></div>' +
                                            '<a href="/project/'+json.id+'">Dashboard</a>' +
                                            '<a href="/project/'+json.id+'/workspaces">Workspaces</a>' +
                                        '</div>' +
                                        '</div>'
                                        );
                                        popup.update();             
                                        loadChartsForCountry(feature.json);
                                });
                                fetched = true;
                                let event = new Event('mapPopupOpen');
                                event.id = feature.properties.id;
                                window.dispatchEvent(event);
                            });
                            marker.on('popupclose', function() {
                                let event = new Event('mapPopupClose');
                                window.dispatchEvent(event);
                            });
                            
                            window.addEventListener('externalPopup', function(e) {
                                if (e.id == feature.properties.id) {
                                    marker.openPopup();    
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
                let menuWidth = document.getElementById("w0").offsetWidth;
                map.fitBounds(bounds, {
                    padding: [50, 50],
                    paddingTopLeft: [menuWidth,0]
                });
            } catch(error) {
                console.error("Error in map widget JS", error);
            }

            function loadChartsForCountry(json) {
                var values,sum,labels,bgColor,icon,title,jsonConfig,canvas;
                            
                var types = Object.keys(json.typeCounts);
                if(types.length > 0) {    
                    values = [];
                    labels = [];
                    sum = Object.keys(json.typeCounts).reduce((sum,key)=>sum+parseFloat(json.typeCounts[key]||0),0);
                    types.forEach(function(type) {
                        values.push(json.typeCounts[type]);
                        let percent = Math.round(((json.typeCounts[type]/sum) * 100));
                        if(isNaN(percent)) percent = 0;
                        labels.push(percent + "% " + type);
                    });
                    bgColor = chroma.scale(['blue', 'white']).colors(types.length);
                    icon = "\u{e90b}";
                    title = "Type";
                    jsonConfig = getCanvasConfig(labels,bgColor,values,icon,title);
                    canvas = document.getElementById('chart1').getContext('2d');
                    let chart = new Chart(canvas, jsonConfig);
                    document.getElementById('js-legend-1').innerHTML = chart.generateLegend();
                }

                types = Object.keys(json.subjectAvailabilityCounts);
                if(types.length > 0) {   
                    values = [];
                    labels = [];
                    sum = Object.keys(json.subjectAvailabilityCounts).reduce((sum,key)=>sum+parseFloat(json.subjectAvailabilityCounts[key]||0),0);
                    types.forEach(function(type) {
                        values.push(json.subjectAvailabilityCounts[type]);
                        let percent = Math.round(((json.subjectAvailabilityCounts[type]/sum) * 100));
                        if(isNaN(percent)) percent = 0;
                        labels.push(percent + "% " + type);
                    });
                    bgColor = chroma.scale(['green', 'orange', 'red']).colors(types.length);
                    icon = "\u{e90a}";
                    title = 'Functionality';
                    jsonConfig = getCanvasConfig(labels,bgColor,values,icon,title);
                    canvas = document.getElementById('chart2').getContext('2d');
                    chart = new Chart(canvas, jsonConfig);
                    document.getElementById('js-legend-2').innerHTML = chart.generateLegend();
                }

                types = Object.keys(json.functionalityCounts);
                if(types.length > 0) {   
                    values = [];
                    labels = [];
                    sum = Object.keys(json.functionalityCounts).reduce((sum,key)=>sum+parseFloat(json.functionalityCounts[key]||0),0);
                    types.forEach(function(type) {
                        values.push(json.functionalityCounts[type]);
                        let percent = Math.round(((json.functionalityCounts[type]/sum) * 100));
                        if(isNaN(percent)) percent = 0;
                        labels.push(percent + "% " + type);
                    });
                    bgColor = chroma.scale(['green', 'orange', 'red']).colors(types.length);
                    icon = "\u{e901}";
                    title = 'Service availability';
                    jsonConfig = getCanvasConfig(labels,bgColor,values,icon,title);
                    canvas = document.getElementById('chart3').getContext('2d');
                    chart = new Chart(canvas, jsonConfig);
                    document.getElementById('js-legend-3').innerHTML = chart.generateLegend();
                }
            }

            function getCanvasConfig(labels,bgColor,values,icon,title) {
                var jsonConfig = 
                {
                    'type' : 'doughnut',
                    'data': {
                        'datasets' : [
                            {
                                'data' : values,
                                'backgroundColor' : bgColor,
                                'label' : 'Types'
                            }],
                        'labels' : labels
                    },
                    'options' : {
                        'tooltips' : {
                                'enabled' : false,
                        },
                        'elements' : {
                            'arc' : {
                                'borderWidth': 0
                            },
                            'center': {
                                'sidePadding': 40,
                                'color': '#a5a5a5',
                                'fontWeight': "normal",
                                'fontStyle': "icomoon",
                                // Facility
                                'text': icon
                            }
                        },
                        'cutoutPercentage': 95,
                        'responsive' : true,
                        'maintainAspectRatio' : false,
                        'legend': {
                            'display': false,
                            'position': 'bottom',
                            'labels': {
                                'boxWidth': 12,
                                'fontSize': 12,
                            }
                        },
                        'title': {
                            'display': true,
                            'text': title
                        },
                        'animation': {
                            'animateScale': true,
                            'animateRotate': true
                        }
                    }
                };
                return jsonConfig;
            }

        })();

JS
        );

        echo Html::endTag('div');
    }


    protected function registerClientScript()
    {
        $this->view->registerAssetBundle(ChartBundle::class);
        $this->view->registerAssetBundle(MapBundle::class);
    }
}
