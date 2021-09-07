<?php


namespace prime\widgets\chart;

use prime\interfaces\HeramsResponseInterface;
use prime\objects\HeramsSubject;
use prime\traits\SurveyHelper;
use prime\widgets\element\Element;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use function iter\take;

class Chart extends Element
{
    public const TYPE_DOUGHNUT = 'doughnut';
    public const TYPE_BAR = 'bar';
    use SurveyHelper;

    /**
     * @var array<HeramsSubject|HeramsResponseInterface>
     */
    public $data = [];
    public $code;

    public string $chartType = self::TYPE_DOUGHNUT;

    /** @var ?string The title to use, if not set will fall back to retrieving it from the survey */
    public $title;

    /**
     * @var bool whether to skip multiple choice / ranking questions with no answer
     */
    public $skipEmpty = false;

    public $map;

    public $colors = [
        'A1' => 'green',
        'A2' => 'orange',
        'A3' => 'red',
        'A4' => 'gray'
    ];

    protected function getMap()
    {
        try {
            $question = $this->findQuestionByCode($this->code);
            switch ($question->getDimensions()) {
                case 0:
                    $map = $this->getAnswers($this->code);
                    break;
                case 1:
                    $map = $this->getAnswers($question->getTitle());
                    break;
                default:
                    die('unknown' . $question->getDimensions());
            }
        } catch (\InvalidArgumentException $e) {
            switch ($this->code) {
                case 'subjectAvailabilityBucket':
                case 'availability':
                case 'fullyAvailable':
                    return $this->getAnswers($this->code);
                case 'causes':
                    $expr = strtr($this->element->project->getMap()->getSubjectExpression(), ['$' => 'x$']);
                    foreach ($this->survey->getGroups() as $group) {
                        foreach ($group->getQuestions() as $question) {
                            if (preg_match($expr, $question->getTitle())) {
                                return $this->getAnswers($question->getTitle());
                            }
                        }
                    }
                    return [];
                default:
                    $map = [];
            }
        }
        return array_merge($this->map ?? [], $map);
    }
    /**
     * @param HeramsResponseInterface[]|HeramsSubject[] $responses
     * @return array
     */
    protected function getDataSet(iterable $responses): array
    {
        // Check question type.
        try {
            $question = $this->findQuestionByCode($this->code);
            switch ($question->getDimensions()) {
                case 0:
                    // Single choice
                    return $this->getCounts($responses, $this->code);

                case 1:
                    // Ranking or multiple choice.
                    return $this->getCounts($responses, $this->code);
                default:
                    die('unknown' . $question->getDimensions());
            }
        } catch (\InvalidArgumentException $e) {
            // If the question is not set, it could be an abstract property.
            $getter = 'get'. ucfirst($this->code);

            // Call this method on each response.
            $counts = [];
            foreach ($responses as $response) {
                $value = $response->$getter();
                if (!$this->skipEmpty || !empty($value)) {
                    if (is_scalar($value)) {
                        $counts[$value] = ($counts[$value] ?? 0) + 1;
                    } else {
                        foreach ($value as $subValue) {
                            $counts[$subValue] = ($counts[$subValue] ?? 0) + 1;
                        }
                    }
                }
            }
            ksort($counts);
            return $counts;
        }
    }

    private function applyMapping(array $map, array $counts)
    {
        $result = [];

        foreach ($counts as $key => $value) {
            $result[$map[$key] ?? $key] = $value;

            unset($map[$key]);
        }

        if (!$this->skipEmpty) {
            foreach ($map as $label) {
                $result[$label] = null;
            }
        }

        return $result;
    }


    /**
     * @param HeramsResponseInterface[] $responses
     */
    private function getCounts(iterable $responses, string $code, int $top = 3): array
    {
        $result = [];
        foreach ($responses as $response) {
            $value = $response->getValueForCode($code);
            if (!empty($value)) {
                if (is_array($value)) {
                    foreach (take($top, $value) as $answer) {
                        $result[$answer] = ($result[$answer] ?? 0) + 1;
                    }
                } else {
                    $result[$value] = ($result[$value] ?? 0) + 1;
                }
            } elseif (!$this->skipEmpty) {
                $result[""] = ($result[""] ?? 0) + 1;
            }
        }
        ksort($result);
        return $result;
    }

    public function run(): string
    {
        $this->registerClientScript();

        $map = $this->getMap();
        $unmappedData = $this->getDataSet($this->data);

        $pointCount = count($unmappedData);
        if ($pointCount > 30) {
            $this->chartType = self::TYPE_BAR;
        }

        if ($this->chartType === self::TYPE_BAR) {
            arsort($unmappedData);
        }

        $colors = [];

        $colorMap = $this->colors;

        foreach ($unmappedData as $code => $count) {
            $colors[] = $colorMap[strtr($code, ['-' => '_'])] ?? '#000000';
        }

        $dataSet = $this->applyMapping($map, $unmappedData);
        $config = [
            'type' => $this->chartType,
            'data' => [
                'datasets' => [
                    [
                        'data' => array_values($dataSet),
                        'backgroundColor' => $colors
                    ]
                ],
                'labels' => array_keys($dataSet)
            ],
            'options' => [
                'layout' => [
                    'padding' => [
//                        'right' => 50
                    ]
                ],
                'scales' => [
                    'xAxes' => [
                        [
                            'display' => false,
                        ]
                    ]
                ],
                'elements' => [
                    $this->chartType == self::TYPE_BAR ? 'topRight' : 'center' => [
                        'text' => new JsExpression('(chart) => {
                            let data = chart.data.datasets[0].data;
                            for (k in  chart.data.datasets[0]._meta) {
                                let meta = chart.data.datasets[0]._meta[k];
                                let total = meta.data.reduce((sum, elem) => {
                                   return elem.hidden ? sum : sum + data[elem._index];
                                }, 0);
                                return total;
                            }
                        }'),
                    ],
                ],
                'legend' => [
                    'position' => 'right',
                    'display' => false,//$this->chartType === self::TYPE_DOUGHNUT,
                    'labels' => [
                        'boxWidth' => 15
                    ]
                ],
                'legendCallback' => new JsExpression('(chart) => {
                    let chartArea = chart.chartArea;
                    let legend = document.createElement("div");
                    legend.classList.add("legend-html");
                    legend.style.marginLeft = "10px";
                    let title = document.createElement("div");
                    title.classList.add("legend-title");
                    title.innerHTML = chart.options.title.text;
                    legend.appendChild(title);
                    let list = document.createElement("div");
                    list.classList.add("legend-list");
                    
                    let dataset = chart.data.datasets[0];
                    let items = dataset._meta[chart.id].data;
                    let value = dataset.data[chart.id];
                    
                    let sum = items.reduce((sum, elem) => {
                        return elem.hidden ? sum : sum + dataset.data[elem._index]
                    }, 0);
                    
                    let count = items.length;
                    if(count > 5)
                        count = Math.ceil(count/2);
                    
                    let item, color, label;
                    let column = document.createElement("div");
                    column.classList.add("column");
                    list.appendChild(column);
                    for (i in  items) {
                        if(items[i].hidden === false) {
                            if(i == count) {
                                column = document.createElement("div");
                                column.classList.add("column");
                                list.appendChild(column);
                            }
                            item = document.createElement("div");
                            item.classList.add("legend-item");
                            color = document.createElement("div");
                            color.classList.add("color");
                            color.style.backgroundColor = items[i]._model.backgroundColor;
                            label = document.createElement("div");
                            label.classList.add("label");
                            let percentage = Math.round(dataset.data[items[i]._index] / sum * 100);
                            if(percentage < 1) percentage = "<1";
                            label.innerHTML = `${items[i]._model.label} <span>(${dataset.data[items[i]._index]} / ${percentage}%)</span>`;
                            item.appendChild(color);
                            item.appendChild(label);
                            column.appendChild(item);
                        }
                    }
                    legend.appendChild(list);
                    return legend.outerHTML;
                }'),
                'cutoutPercentage' => 83,

                'title' => [
                    'display' => false,
                    'text' => $this->title ?? $this->getTitleFromCode($this->code)
                ],
                'responsive' => true,
                'maintainAspectRatio' => false,
                'aspectRatio' => 1,
                'tooltips' => [
                    'enabled' => false,
                    'bodyFontSize' => 20,
                    /*'callbacks' => [
                        'label' => new JsExpression('function(item, data) {
                            let value = data.datasets[item.datasetIndex].data[item.index];
                            let label = data.labels[item.index] || "";
                            let meta = this._chart.data.datasets[0]._meta;
                            for (let key in meta) {
                                let sum = meta[key].data.reduce((sum, elem) => {
                                    return elem.hidden ? sum : sum + data.datasets[item.datasetIndex].data[elem._index]
                                }, 0);
                                let percentage = Math.round(100 * value / sum) + "%";
                                return `${label}: ${value} (${percentage})`;
                            }
                        }'),

                    ]*/

                ]
            ]
        ];
        $jsConfig = Json::encode($config);

        $id = Json::encode($this->getId());
        $this->view->registerJs(<<<JS
        (function() {
            let container = document.getElementById($id);
            let canvasId = $id+"-canvas";
            let canvas = document.getElementById(canvasId);
            let ctx = canvas.getContext('2d');
            let chart = new Chart(ctx, $jsConfig);
            canvas.closest('.element').insertAdjacentHTML('beforeend', chart.generateLegend());
            chart.options.tooltips.custom = function(tooltip) {
                // Tooltip Element
                let tooltipId = 'chartjs-tooltip'+canvasId;
                var tooltipEl = document.getElementById(tooltipId);
                if(tooltipEl == null) {
                    tooltipEl = document.createElement("div");
                    tooltipEl.classList.add('tooltip');
                    tooltipEl.id = tooltipId;
                    canvas.closest('.element').appendChild(tooltipEl);
                }    
                // Hide if no tooltip
                if (tooltip.opacity === 0) {
                    tooltipEl.style.opacity = 0;
                    return;
                }

                // Set caret Position
                tooltipEl.classList.remove('above', 'below', 'no-transform');
                if (tooltip.yAlign) {
                    tooltipEl.classList.add(tooltip.yAlign);
                } else {
                    tooltipEl.classList.add('no-transform');
                }

                function getBody(bodyItem) {
                    return bodyItem.lines;
                }

                // Set Text
                if (tooltip.body) {
                    let meta = chart.data.datasets[0]._meta;
                    let percentage;
                    for (let key in meta) {
                        let sum = meta[key].data.reduce((sum, elem) => {
                            return elem.hidden ? sum : sum + chart.data.datasets[tooltip.dataPoints[0].datasetIndex].data[elem._index]
                        }, 0);
                         percentage = Math.round(100 * chart.data.datasets[tooltip.dataPoints[0].datasetIndex].data[tooltip.dataPoints[0].index] / sum) + "%";
                    }
                    

                    var titleLines = tooltip.title || [];
                    var bodyLines = tooltip.body.map(getBody);

                    var innerHtml = '<thead>';

                    titleLines.forEach(function(title) {
                        innerHtml += '<tr><th>' + title + '</th></tr>';
                    });
                    innerHtml += '</thead><tbody>';

                    bodyLines.forEach(function(body, i) {
                        var colors = tooltip.labelColors[i];
                        var style = 'background:' + colors.backgroundColor;
                        var span = '<span class="chartjs-tooltip-key" style="' + style + '"></span>';
                        innerHtml += '<tr><td>' + span + body + ' ('+percentage+')</td></tr>';
                    });
                    innerHtml += '</tbody>';

                    var tableRoot = tooltipEl.querySelector('table');
                    if(tableRoot == null) {
                        tableRoot = document.createElement("table");
                        tooltipEl.appendChild(tableRoot);
                    }
                    tableRoot.innerHTML = innerHtml;
                }

                var positionY = this._chart.canvas.offsetTop;
                var positionX = this._chart.canvas.offsetLeft;

                // Display, position, and set styles for font
                tooltipEl.style.opacity = 1;
                tooltipEl.style.left = positionX + tooltip.caretX + 'px';
                tooltipEl.style.top = positionY + tooltip.caretY + 'px';
                tooltipEl.style.fontFamily = tooltip._bodyFontFamily;
                tooltipEl.style.fontSize = tooltip.bodyFontSize;
                tooltipEl.style.fontStyle = tooltip._bodyFontStyle;
                tooltipEl.style.padding = tooltip.yPadding + 'px ' + tooltip.xPadding + 'px';
            };

            function legendItemClick() {
                $(this).toggleClass('meta-hidden');
                let meta = chart.getDatasetMeta(0);
                let index = $(this).index();
                meta.data[index].hidden = meta.hidden === null ? !meta.data[index].hidden : null;
                chart.update();
            }
            
            let items = container.getElementsByClassName("legend-item");
            Array.from(items).forEach(function(item) {
                item.addEventListener('click', legendItemClick);
            });

        })();
JS
        );

        // Remove closing </div>
        $parent = substr(parent::run(), 0, -6);
        $parent .= Html::tag('canvas', '', [
            'id' => "{$this->getId()}-canvas"
        ]);
        $parent .= '</div>';
        return $parent;
    }

    protected function registerClientScript(): void
    {
        $this->view->registerAssetBundle(ChartBundle::class);
    }
}
