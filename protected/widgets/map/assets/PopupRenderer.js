
class PopupRenderer {

    
    constructor(popup, url, translations)
    {
        this.popup = popup;
        this.url = url;
        this.translations = translations;
    }

    renderLoading()
    {
        let content = document.createElement('div');
        content.classList.add('loader-wrapper');
        let logo = document.createElement('div');
        logo.classList.add('loader-anim');
        logo.setAttribute('style' , "background-image: url('/img/herams_icon.png');");
        content.appendChild(logo);
        let title = document.createElement('h1');
        title.textContent = this.translations["loading-text"];
        content.appendChild(title);
        let loader = document.createElement('div');
        loader.classList.add('loader-anim');
        loader.setAttribute('style' , "background-image: url('/img/loader.svg');");
        content.appendChild(loader);
        this.popup.setContent(content);
        this.popup.update();
    }

    renderInactive()
    {
        let content = document.createElement('div');
        content.classList.add('project-summary');
        let title = document.createElement('h1');
        title.textContent = this.data.name;
        content.appendChild(title);
        content.innerHTML += '<h2>'+this.translations["inactive"]+'</h2>';
        this.popup.setContent(content);
        this.popup.update();
    }

    renderFailed()
    {
        let content = document.createElement('div');
        content.classList.add('project-summary');
        let title = document.createElement('h1');
        title.textContent = this.translations["loading-failed"];
        content.appendChild(title);
        content.innerHTML += '<h2>'+this.translations["loading-error"]+'</h2>';
        content.innerHTML += '<p>'+this.translations["refresh-infos"]+'</p>';
        let button = document.createElement('button');
        button.classList.add('btn');
        button.textContent = this.translations["refresh"];
        button.addEventListener("click", _ => this.render());
        content.appendChild(button);
        this.popup.setContent(content);
        this.popup.update();
    }

    static createStat(name, value)
    {
        let count = document.createElement('strong');
        count.textContent = value;

        let element = document.createElement('div');
        element.classList.add('stat');
        element.textContent = ' ' + name;
        element.prepend(count);
        return element;

    }

    async render()
    {
        this.renderLoading();
        if (!this.data) {
            let response = await fetch(this.url);
            if (response.ok) {
                this.data = await response.json();
            } else {
                return this.renderFailed();
            }
        }

        if (this.data.status !== 0) {
            return this.renderInactive();
        }

        let content = document.createElement('div');
        content.classList.add('project-summary');
        let title = document.createElement('h1');
        title.textContent = this.data.name;
        content.appendChild(title);
        let grid = document.createElement('div');
        content.appendChild(grid);

        grid.appendChild(PopupRenderer.createStat(this.translations["health-facilities"], this.data.facilityCount));
        grid.appendChild(PopupRenderer.createStat(this.translations["contributors"], this.data.contributorCount));
        grid.append(document.createElement('hr'));

        let charts = [];
        charts.push(PopupRenderer.buildChart(this.translations["type"], "\u{e90b}", this.data.typeCounts, [{"key":"Tertiary",label:this.translations["tertiary"]},{"key":"Secondary","label":this.translations["secondary"]},{"key":"Primary","label":this.translations["primary"]},{"key":"Other","label":this.translations["other"]}], ['blue', 'white']));
        charts.push(PopupRenderer.buildChart(this.translations["functionality"], "\u{e90a}", this.data.functionalityCounts, [{"key":"Full","label":this.translations["fully-functional"]},{"key":"Partial","label":this.translations["partially-functional"]},{"key":"None","label":this.translations["not-functional"]}], ['green', 'orange', 'red']));
        charts.push(PopupRenderer.buildChart(this.translations["service-availability"], "\u{e901}", this.data.subjectAvailabilityCounts, [{"key":"Full","label":this.translations["fully-available"]},{"key":"Partial","label":this.translations["partially-available"]},{"key":"None","label":this.translations["not-available"]}], ['green', 'orange', 'red']));
        charts = charts.filter(function (el) {
            return el.innerHTML !== "";
        });
        
        if (charts.length > 0) {
            let span = 'span'+(6 / charts.length);
            charts.map(container => container.classList.add(span));
            grid.append(...charts);
        } else {
            let content = document.createElement('div');
            content.classList.add('full-width');
            content.innerHTML += '<h2>'+this.translations["in-progress"]+'</h2>';
            grid.append(content);
        }

        
        if (this.data._links.dashboard) {
            let a = document.createElement('a');
            a.textContent = this.data._links.dashboard.title;
            a.href = this.data._links.dashboard.href;
            grid.appendChild(a);
        }

        if (this.data._links.workspaces) {
            let a = document.createElement('a');
            a.textContent = this.data._links.workspaces.title;
            a.href = this.data._links.workspaces.href;
            grid.appendChild(a);
        }

        this.popup.setContent(content);
        this.popup.update();
    }



    static getChartConfig(labels, bgColor, values, icon, title)
    {
        return {
            'type': 'doughnut',
            'data': {
                'datasets': [
                    {
                        'data': values,
                        'backgroundColor': bgColor,
                        'label': 'Types'
                }],
                'labels': labels
            },
            'options': {
                'tooltips': {
                    'enabled': false,
                },
                'elements': {
                    'arc': {
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
                'responsive': true,
                'maintainAspectRatio': false,
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
    }

    static buildChart(title, icon, datas, legends, colors)
    {
        let sum = Object.values(datas).reduce((sum, value) => sum + value, 0);
        if (sum > 0) {
            let labels = {};
            
            for (let i in legends) {
                if (!(legends[i].key in datas)) {
                    labels[`-- ${legends[i].label}`] = 0;
                    continue;
                }
                
                let percent =  Math.round((datas[legends[i].key] / sum) * 100);
                if (percent < 1) {
                    labels[`< 1% ${legends[i].label}`] = percent;
                } else {
                    labels[`${percent}% ${legends[i].label}`] = percent;
                }
            }

            let config = PopupRenderer.getChartConfig(Object.keys(labels), chroma.scale(colors).colors(Object.values(labels).length), Object.values(labels), icon, title);

            let canvas = document.createElement('canvas');

            let chart = new Chart(canvas.getContext('2d'), config);

            let container = document.createElement('div');
            container.classList.add('chart');
            let chartWrapper = document.createElement('div');
            chartWrapper.appendChild(canvas);
            container.appendChild(chartWrapper);
            container.insertAdjacentHTML('beforeend', chart.generateLegend());
            return container;
        }
        return document.createElement('div');
    }

}





