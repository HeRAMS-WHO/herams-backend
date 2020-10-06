
class DashboardMapRenderer {


    constructor(map)
    {
        this.layers = [];
        DashboardMapRenderer.rmax = 30;
        this.map = map;
    }



    SetData(data, baseLayers, code)
    {
        DashboardMapRenderer.code = code;
        this.data = data;

        for (let baseLayer of baseLayers) {
            switch (baseLayer.type) {
                case 'tileLayer':
                    L.tileLayer(baseLayer.url, baseLayer.options || {}).addTo(this.map);
                    break;
            }
        }

        this.allMarkers = L.layerGroup();
        this.markerclusters = L.markerClusterGroup({
            maxClusterRadius: 2 * DashboardMapRenderer.rmax,
            iconCreateFunction: this.defineClusterIcon,
            spiderfyOnMaxZoom: false,
            disableClusteringAtZoom: 12
        })
        this.map.addLayer(this.markerclusters);

    }

    RenderMap()
    {
        if (this.data.length == 0) {
            return;
        }

        for (let set of this.data) {
            let layer = L.geoJSON(set.features, {
                pointToLayer: this.defineFeature,
                onEachFeature: this.defineFeaturePopup,
            });
            this.markerclusters.addLayer(layer);
            this.allMarkers.addLayer(layer);

            let legend = document.createElement('span');
            legend.classList.add('legend');
            legend.style.setProperty('--color', set.color);
            legend.title = set.features.length;
            legend.textContent = set.title;
            this.layers[legend.outerHTML] = layer;
        }

        this.bounds = this.markerclusters.getBounds();
    }


    defineClusterIcon(cluster)
    {

        var children = cluster.getAllChildMarkers(),
            n = children.length, //Get number of markers in cluster
            strokeWidth = 1, //Set clusterpie stroke width
            rad = (n < 10 ? 12 : n < 100 ? 8 : n < 1000 ? 4 : 0),
            r = DashboardMapRenderer.rmax - 2 * strokeWidth - rad, //Calculate clusterpie radius...
            iconDim = (r + strokeWidth) * 2, //...and divIcon dimensions(leaflet really want to know the size)
            dataPie = d3.nest() //Build a dataset for the pie chart
                .key(function (d) {
                    return d.feature.properties.data[DashboardMapRenderer.code];
                })
                .entries(children, d3.map),
            //bake some svg markup
            html = DashboardMapRenderer.renderPie({
                dataPie: dataPie,
                valueFunc: function (d) {
                    return d.values.length;
                },
                colorFunc: function (d) {
                    return d.data.values[0].feature.properties.color;
                },
                strokeWidth: 1,
                outerRadius: r,
                innerRadius: r - 10,
                pieClass: 'cluster-pie',
                pieLabel: n,
                pieLabelClass: 'marker-cluster-pie-label',
                pathClassFunc: function (d) {
                    return "category-" + d.data.key;
                },
                pathTitleFunc: function (d) {
                    return d.data.values[0].feature.properties.title;
                }
            }),
            //Create a new divIcon and assign the svg markup to the html property
            myIcon = new L.DivIcon({
                html: html,
                className: 'marker-cluster',
                iconSize: new L.Point(iconDim, iconDim)
            });
        return myIcon;
    }

    defineFeature(feature, latlng)
    {

        var categoryVal = feature.properties.data[DashboardMapRenderer.code];
        var myClass = 'marker category-' + categoryVal;
        var myIcon = L.divIcon({
            className: myClass,
            iconSize: null
        });

        return L.circleMarker(latlng, {
            icon: myIcon,
            radius: 6,
            color: feature.properties.color,
            weight: 1,
            opacity: 1,
            fillOpacity: 0.8
        });
    }

    defineFeaturePopup(feature, layer)
    {
        layer.bindPopup(function (e) {
            return "<div class='hf-summary'>" +
                "<h2>" + feature.properties.title + "</h2>" +
                "<a href='" + e.feature.properties.workspace_url + "' class='btn btn-primary'>" + e.feature.properties.workspace_title + "</a>" +
                "</div>";
        }, { 'className': "hf-popup", offset: L.point(1, -2) });
    }


    static renderPie(options)
    {
        /*data and valueFunc are required*/
        if (!options.dataPie || !options.valueFunc) {
            return '';
        }
        var dataPie = options.dataPie,
            valueFunc = options.valueFunc,
            r = options.outerRadius ? options.outerRadius : 28, //Default outer radius = 28px
            rInner = options.innerRadius ? options.innerRadius : r - 10, //Default inner radius = r-10
            colorFunc = options.colorFunc ? options.colorFunc : function () {
                return 'white';
            }, //Class for each path
            strokeWidth = options.strokeWidth ? options.strokeWidth : 1, //Default stroke is 1
            pathClassFunc = options.pathClassFunc ? options.pathClassFunc : function () {
                return '';
            }, //Class for each path
            pathTitleFunc = options.pathTitleFunc ? options.pathTitleFunc : function () {
                return '';
            }, //Title for each path
            pieClass = options.pieClass ? options.pieClass : 'marker-cluster-pie', //Class for the whole pie
            pieLabel = options.pieLabel ? options.pieLabel : d3.sum(dataPie, valueFunc), //Label for the whole pie
            pieLabelClass = options.pieLabelClass ? options.pieLabelClass : 'marker-cluster-pie-label',//Class for the pie label

            origo = (r + strokeWidth), //Center coordinate
            w = origo * 2, //width and height of the svg element
            h = w,
            donut = d3.layout.pie(),
            arc = d3.svg.arc().innerRadius(rInner).outerRadius(r);

        //Create an svg element
        var svg = document.createElementNS(d3.ns.prefix.svg, 'svg');
        //Create the pie chart
        var vis = d3.select(svg)
            .data([dataPie])
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
            .attr('fill', colorFunc)
            .attr('d', arc)
            .append('svg:title')
            .text(pathTitleFunc);

        vis.append('text')
            .attr('x', origo)
            .attr('y', origo)
            .attr('class', pieLabelClass)
            .attr('text-anchor', 'middle')
            //.attr('dominant-baseline', 'central')
            /*IE doesn't seem to support dominant-baseline, but setting dy to .3em does the trick*/
            .attr('dy', '.3em')
            .text(pieLabel);
        //Return the svg-markup rather than the actual element
        return DashboardMapRenderer.serializeXmlNode(svg);
    }


    /*Helper function*/
    static serializeXmlNode(xmlNode)
    {
        if (typeof window.XMLSerializer != "undefined") {
            return (new window.XMLSerializer()).serializeToString(xmlNode);
        } else if (typeof xmlNode.xml != "undefined") {
            return xmlNode.xml;
        }
        return "";
    }




    /*Function for generating a legend with the same categories as in the clusterPie*/
    RenderLegend(title)
    {
        let layerControl = L.control.layers([], this.layers, {
            collapsed: false,
            position: 'topright'
        });
        layerControl.addTo(this.map);
        this.legend = $(layerControl.getContainer());
        this.legend.addClass("legend-container");
        this.legend.addClass("has-clusters");
        this.legend.prepend("<p>" + title + "</p>");
        this.legend.append('<span class="clustertoggle">Show / Hide Clusters</span>');

        $('.legend').on('click', () => {
            this.removeClusters();
        });

        $('.clustertoggle').on('click', () => {
            this.toggleClusters();
        });

    }

    removeClusters()
    {
        if (this.map.hasLayer(this.markerclusters)) {
            this.map.removeLayer(this.markerclusters);
            this.map.addLayer(this.allMarkers);
            this.legend.removeClass("has-clusters");
        }
    }

    addClusters()
    {
        if (this.map.hasLayer(this.allMarkers)) {
            this.map.removeLayer(this.allMarkers);
            this.map.addLayer(this.markerclusters);
            this.legend.addClass("has-clusters");
        }
    }

    toggleClusters()
    {
        $('.legend').off('click');
        if (this.map.hasLayer(this.markerclusters)) {
            this.removeClusters();
        } else {
            this.addClusters()
        }
        $('.legend').on('click', () => {
            this.removeClusters();
        });

    }
}





