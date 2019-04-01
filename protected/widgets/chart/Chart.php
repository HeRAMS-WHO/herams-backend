<?php


namespace prime\widgets\chart;


use prime\objects\HeramsResponse;
use prime\traits\SurveyHelper;
use prime\widgets\element\Element;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use function iter\map;
use function iter\take;
use function iter\toArray;

class Chart extends Element
{
    public const TYPE_DOUGHNUT = 'doughnut';
    public const TYPE_BAR = 'bar';
    use SurveyHelper;

    /** @var iterable */
    public $data = [];
    public $code;

    public $type = self::TYPE_DOUGHNUT;

    /** @var SurveyInterface */
    public $survey;

    /** @var ?string The title to use, if not set will fall back to retrieving it from the survey */
    public $title;

    /**
     * @var bool whether to skip multiple choice / ranking questions with no answer
     */
    public $skipEmpty = false;

    public $map;

    public $colors = ['green' , 'orange', 'red'];

    public $notRelevantColor = 'gray';

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
            $map = [];
        }
        return array_merge($this->map ?? [], $map);
    }
    /**
     * @param HeramsResponse[] $responses
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
                    return $this->getCounts($responses, [$this->code]);

                case 1:
                    // Ranking or multiple choice.
                    $titles = take(3, map(function (QuestionInterface $subQuestion) use ($question) {
                        return "{$question->getTitle()}[{$subQuestion->getTitle()}]";
                    }, $question->getQuestions(0)));
                    return $this->getCounts($responses, $titles);
                default:
                    die('unknown' . $question->getDimensions());
            }
        } catch (\InvalidArgumentException $e) {
            // If the question is not set, it could be an abstract property.
            $getter = 'get'. ucfirst($this->code);

            // Call this method on each response.
            $counts = [];
            foreach($responses as $response) {
                $value = $response->$getter();
                if (!$this->skipEmpty || !empty($value)) {
                    if (is_scalar($value)) {
                        $counts[$value] = ($counts[$value] ?? 0) + 1;
                    } else {
                        foreach($value as $subValue) {
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

        foreach($map as $key => $label) {
            if ($this->skipEmpty && !array_key_exists($key, $counts)) {
                continue;
            }

            $result[$label] = $counts[$key] ?? null;
            unset($counts[$key]);
        }

        foreach($counts as $key => $value) {
            $result[$key] = $value;
        }


        return $result;
    }


    /**
     * @param HeramsResponse[] $responses
     * @param string[] $codes
     */
    private function getCounts(iterable $responses, iterable $codes): array
    {
        $codes = toArray($codes);
        $result = [];
        foreach($responses as $response) {
            $empty = true;
            foreach($codes as $code) {
                $value = $response->getValueForCode($code);
                if (!empty($value)) {
                    $empty = false;
                    $result[$value] = ($result[$value] ?? 0) + 1;
                }
            }
            if ($empty && !$this->skipEmpty) {
                $result[""] = ($result[""] ?? 0) + 1;
            }
        }
        ksort($result);
        return $result;
    }

    public function run()
    {
        $this->registerClientScript();


        $map = $this->getMap();
        $unmappedData = $this->getDataSet($this->data);
        $dataSet = $this->applyMapping($map, $unmappedData);

        $pointCount = count($dataSet);
        if ($pointCount > 30) {
            $this->type = self::TYPE_BAR;
        }

        $colorCount = max(count($map), $pointCount) - (empty($this->notRelevantColor) ? 0 : 1);

        if (!empty($map)) {
            $bitMap = [];
            foreach (array_keys($map) as $i => $k) {
                $bitMap[] = isset($unmappedData[$k]);
            }
        }

        $baseColors = Json::encode($this->colors);
        $bitMap = Json::encode($bitMap ?? []);
        $notRelevantColor = Json::encode($this->notRelevantColor);
        $colorJs = new JsExpression(<<<JS
(function() {
    let bitmap = $bitMap;
    let colors = chroma.scale($baseColors).colors($colorCount).filter((element, index) => {
        return bitmap.length == 0 || bitmap[index];
    });
    
    if ($notRelevantColor != null) {
        colors.push($notRelevantColor);    
    }
    return colors;
})(chroma)



JS
            );
        $config = [
            'type' => $this->type,
            'data' => [
                'datasets' => [
                    [
                        'data' => array_values($dataSet),
                        'backgroundColor' => $colorJs
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
                    'center' => [
                        'text' => new JsExpression('(chart) => {
                            let data = chart.data.datasets[0].data;
                            for (k in  chart.data.datasets[0]._meta) {
                                let meta = chart.data.datasets[0]._meta[k];
                                let total = meta.data.reduce((sum, elem) => {
                                   return elem.hidden ? sum : sum + data[elem._index];
                                }, 0);
                                return total;
                            }
                            
                            
                        console.log(chart); 
                        }')
                    ]
                ],
                'legend' => [
                    'position' => 'right',
                    'display' => $this->type === self::TYPE_DOUGHNUT,
                    'labels' => [
                        'boxWidth' => 15
                    ]
                ],
                'cutoutPercentage' => 80,

                'title' => [
                    'display' => $this->type === self::TYPE_DOUGHNUT,
                    'text' => $this->title ?? $this->getTitle()
                ],
                'responsive' => true,
                'maintainAspectRatio' => false,
                'tooltips' => [
                    'callbacks' => [
                        'label' => new JsExpression('function(item, data) { 
                        console.log(this, item, data);
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

                    ]

                ]
            ]
        ];
        $jsConfig = Json::encode($config);


        echo Html::tag('canvas', '', [
            'id' => "{$this->getId()}-canvas"
        ]);

        $canvasId = Json::encode("{$this->getId()}-canvas");
        $this->view->registerJs(<<<JS
        (function() {
            let ctx = document.getElementById($canvasId).getContext('2d');
            let chart = new Chart(ctx, $jsConfig);
            
        })();
JS
        );
        parent::run();
    }

    protected function registerClientScript()
    {
       $this->view->registerAssetBundle(ChartBundle::class);



    }


}
