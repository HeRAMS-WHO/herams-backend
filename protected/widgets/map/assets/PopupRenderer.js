
class PopupRenderer {

    #popup;
    #url;

    #data;

    constructor(popup, url)
    {
        this.#popup = popup;
        this.#url = url;
    }

    renderInactive()
    {
        let content = document.createElement('div');
        let title = document.createElement('h1');
        title.textContent = this.#data.title;
        content.appendChild(title);
        content.innerHTML += '<h2>In Progress</h2>';
        content.innerHTML += '<p>This project is in the process of being set up. When it becomes active this popup will show key metrics and allow access to the project dashboard.</p>';
        this.#popup.setContent(content);
        this.#popup.update();
    }

    static #createStat = (name, value) => {
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
        if (!this.#data) {
            let response = await fetch(this.#url);
            this.#data = await response.json();
        }

        if (this.#data.status !== 'ongoing') {
            return this.renderInactive();
        }

        let buttons = [];


        let content = document.createElement('div');
        content.classList.add('project-summary');
        let title = document.createElement('h1');
        title.textContent = this.#data.title;
        content.appendChild(title);
        let grid = document.createElement('div');
        content.appendChild(grid);

        grid.appendChild(PopupRenderer.#createStat('Health facilities', this.#data.facilityCount));
        grid.appendChild(PopupRenderer.#createStat('Contributors', this.#data.contributorCount));
        grid.append(document.createElement('hr'));

        grid.appendChild(PopupRenderer.#buildChart('Type', "\u{e90b}", this.#data.typeCounts, [
            {"key": "Tertiary", "label": "Tertiary"},
            {"key": "Secondary", "label": "Secondary"},
            {"key": "Primary", "label": "Primary"},
            {"key": "Other", "label": "Other"}
        ], ['blue', 'white']));

        grid.appendChild(PopupRenderer.#buildChart('Functionality', "\u{e90b}", this.#data.functionalityCounts, [
            {"key": "Full", "label":"Fully functional"},
            {"key": "Partial", "label":"Partially functional"},
            {"key": "None", "label":"Not functional"}
        ], ['green', 'orange', 'red']));

        grid.appendChild(PopupRenderer.#buildChart('Service availability', "\u{e90b}", this.#data.subjectAvailabilityCounts, [
            {"key":"Full","label":"Fully available"},
            {"key":"Partial","label":"Partially available"},
            {"key":"None","label":"Not available"}
        ], ['green', 'orange', 'red']));

        if (this.#data.links.dashboard) {
            let a = document.createElement('a');
            a.textContent = 'Dashboard';
            a.href = this.#data.links.dashboard;
            grid.appendChild(a);
        }

        if (this.#data.links.workspaces) {
            let a = document.createElement('a');
            a.textContent = 'Workspaces';
            a.href = this.#data.links.workspaces;
            grid.appendChild(a);
        }

                // content += '<div class="no-data full-width"><h2>In Progress</h2>' +
                //     '<p>Datas for this project are being collected. When it becomes active this popup will show key metrics and allow access to the project dashboard.</p>'+
                //     '</div>';
        this.#popup.setContent(content);
        this.#popup.update();
    }



    static #getChartConfig = (labels, bgColor, values, icon, title) => {
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

    static #buildChart = (title, icon, typeCounts, items, colors) => {
        if (Object.keys(typeCounts).length > 0) {
            let sum = Object.values(typeCounts).reduce((sum, value) => sum + value, 0);
            let labels = {};

            items.forEach((item) => {
                if (typeCounts[item.key]) {
                    let percent = Math.round((typeCounts[item.key] / sum) * 100);
                    if (percent < 1) {
                        labels[`< 1% ${item.label}`] = percent;
                    } else {
                        labels[`${percent}% ${item.label}`] = percent;
                    }
                } else {
                    labels[`-- ${item.label}`] = 0;
                }
            });

            let config = PopupRenderer.#getChartConfig(Object.keys(labels), chroma.scale(colors).colors(items.length), Object.values(labels), icon, title);

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
    }

}





