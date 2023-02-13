<?php

declare(strict_types=1);
namespace prime\widgets\map;

use prime\widgets\chart\ChartBundle;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

final class Map extends Widget
{
    public const TILE_LAYER = 'tileLayer';

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

    public $options = [
        'class' => [
            'map',
        ],
    ];

    public $center = [8.6753, 9.0820];

    public $zoom = 5.4;

    public $minZoom = 3;

    public $maxZoom = 5;

    public array|JsExpression $colors;

    public float $markerRadius = 12.5;

    public function init(): void
    {
        $this->colors = $this->colors ?? new JsExpression('chroma.brewer.OrRd');
        parent::init();
    }

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
            'leaflet' => [
                'preferCanvas' => true,
                'center' => $this->center,
                'zoom' => $this->zoom,
                'zoomControl' => false,
                'maxZoom' => $this->maxZoom,
                'minZoom' => $this->minZoom,
            ],
            'projectsUri' => Url::to(['/api/projects']),
            'markerRadius' => $this->markerRadius,
            'colors' => $this->colors,
            'translations' => [
                'health-facilities' => \Yii::t('app', 'Health facilities'),
                'contributors' => \Yii::t('app', 'Contributors'),
                'type' => \Yii::t('app', 'Type'),
                'functionality' => \Yii::t('app', 'Functionality'),
                'service-availability' => \Yii::t('app', 'Service Availability'),
                'full' => \Yii::t('app', 'Full'),
                'partial' => \Yii::t('app', 'Partial'),
                'none' => \Yii::t('app', 'None'),
                'tertiary' => \Yii::t('app', 'Tertiary'),
                'secondary' => \Yii::t('app', 'Secondary'),
                'primary' => \Yii::t('app', 'Primary'),
                'other' => \Yii::t('app', 'Other'),
                'unknown' => \Yii::t('app', 'Unknown'),
                'fully-functional' => \Yii::t('app', 'Fully functional'),
                'partially-functional' => \Yii::t('app', 'Partially functional'),
                'not-functional' => \Yii::t('app', 'Not functional'),
                'fully-available' => \Yii::t('app', 'Fully available'),
                'partially-available' => \Yii::t('app', 'Partially available'),
                'not-available' => \Yii::t('app', 'Not available'),
                'loading-text' => \Yii::t('app', 'Loading project summary'),
                'inactive' => \Yii::t('app', 'in progress'),
                'loading-failed' => \Yii::t('app', 'Loading failed'),
                'loading-error' => \Yii::t('app', 'Loading Error'),
                'refresh' => \Yii::t('app', 'Refresh'),
                'refresh-infos' => \Yii::t('app', 'Try refreshing the project'),
                'in-progress' => \Yii::t('app', 'In Progress'),
            ],
            'baseLayers' => $this->baseLayers,
        ]);

        $this->view->registerJs(<<<JS
        (function() {
            try {
                const config = $config;
                const  map = L.map($id, config.leaflet);
                
                const markerCluster = L.markerClusterGroup(
                {
                    zoomToBoundsOnClick : false,
                    spiderfyOnMaxZoom: false,
                    showCoverageOnHover: false,
                    maxClusterRadius: 10,
                    iconCreateFunction: function(cluster) {
                        const color = cluster.getAllChildMarkers()[0].options.color
                        const span = L.DomUtil.create('span')
                        span.style.backgroundColor = color
                        span.style.borderColor = color
                        span.textContent = cluster.getChildCount()
                        return L.divIcon({ html: span})
                    }
                });
                
                map.addLayer(markerCluster);

                var popupList = L.popup( {
                                    maxWidth: "auto",
                                    closeButton: false
                                });
                const renderer = new PopupListRenderer(popupList, config.translations);                
                markerCluster.on('clusterclick', function (a) {
                    map.flyTo(a.latlng, map.getZoom(), {
                        animate: true,
                        duration: 0.5
                    });
                    popupList.setLatLng(a.latlng);
                    renderer.render(a.layer.getAllChildMarkers());
                    popupList.openOn(map);
                });
                
                (async () => {
                    const response = await fetch(config.projectsUri)
                    const projects = await response.json()
                    const collections = {}
                    projects.forEach((project) => {
                       if (!collections.hasOwnProperty(project.status)) {
                           collections[project.status] = {
                               type: "FeatureCollection",
                               title: project.statusText,
                               features: []
                           }
                       } 
                       collections[project.status]['features'].push({
                         type: "Feature",
                         geometry: {
                             type: "Point",
                             coordinates: [project.longitude, project.latitude]
                         },
                         properties: {
                             id: project.id,
                             title: project.name,
                             // This is a relative API URL, so we need to manually route this through te proxy endpoint.
                             url: document.querySelector('meta[name=api]').content + project._links.summary.href
                         }
                       })
                       
                    });
                    
                    // Create leaflet entities
                    const bounds = [];
                    const layers = [];
                    const markers = [];
                    const scale = chroma.scale(config.colors).colors(collections.length);
                    for (const set of Object.values(collections)) {
                        const color = scale.pop();
                        let layer = L.geoJSON(set.features, {
                            pointToLayer: function(feature, latlng) {
                                bounds.push(latlng);
                                let marker = L.circleMarker(latlng, {
                                    radius: config.markerRadius,
                                    color: color,
                                    weight: 2.5,
                                    opacity: 1,
                                    fillOpacity: 0.8
                                });
                                marker.feature = feature;
                                
                                let popup = marker.bindPopup("", {
                                    maxWidth: "auto",
                                    closeButton: false
                                }).getPopup();
    
                                // On the first open fetch remote content
                                marker.renderer = new PopupRenderer(popup, feature.properties.url, config.translations);
                                marker.on('popupopen', () => {
                                    marker.renderer.render();
                                    let event = new Event('mapPopupOpen');
                                    event.id = feature.properties.id;
                                    map.once('moveend', function(){
                                        window.dispatchEvent(event);
                                    } );
                                    map.flyTo(marker.getLatLng(), map.getZoom(), {
                                        animate: true,
                                        duration: 0.3
                                    });
                                });
                                marker.on('popupclose', function() {
                                    let event = new Event('mapPopupClose');
                                    window.dispatchEvent(event);
                                });
                                
                                let tooltip = marker.bindTooltip(feature.properties.title, {className: 'tooltip'});
                                window.addEventListener('externalPopup', function(e) {
                                    if (e.id == feature.properties.id) {
                                        showPopupForMarker(marker);
                                    }
                                });
                                markers[feature.properties.id] = marker;
                                return marker;
                            }, 
                            onEachFeature: function(feature, layer) {
                            }
                        });
                        layers.push(layer);
                    }
                    markerCluster.addLayers(layers);
                    const menu = document.getElementById("w0");
                    if (menu) {
                        map.fitBounds(bounds, {
                            padding: [50, 50],
                            paddingTopLeft: [menu.offsetWidth, 0]
                        });
                    }
                })()
                
                
                window.map = map;
                for (let baseLayer of config.baseLayers) {
                    switch (baseLayer.type) {
                        case 'tileLayer':
                            L.tileLayer(baseLayer.url, baseLayer.options || {}).addTo(map);
                            break;
                    }
                }
                
                // /*
                
                
                


                window.addEventListener('click', function(e) {
                    if (e.target.matches('.project-list .project-item[data-id]')) {
                        const event = new Event('externalPopup');
                        event.id = e.target.getAttribute('data-id');
                        window.dispatchEvent(event);
                    }
                });

                
                
                
                L.control.zoom({
                    position: 'bottomright'
                }).addTo(map);
                L.control.scale({
                    metric: true,
                    imperial: false
                }).addTo(map);
                
            } catch(error) {
                console.error("Error in map widget JS", error);
            }

            
            function showPopupForMarker(marker) {
                let popup = marker.getPopup();
                map.once('moveend', function(){
                    popup.setLatLng(marker.getLatLng());
                    popup.openOn(map);
                } );
                map.flyTo(marker.getLatLng(), map.getZoom(), {
                    animate: true,
                    duration: 0.5
                });
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
