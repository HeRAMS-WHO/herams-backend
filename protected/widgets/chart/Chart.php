<?php


namespace prime\widgets\chart;


use prime\objects\HeramsResponse;
use prime\traits\SurveyHelper;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;
use function iter\map;
use function iter\take;
use function iter\toArray;

class Chart extends Widget
{
    public const TYPE_DOUGHNUT = 'doughnut';
    public const TYPE_BAR = 'bar';
    use SurveyHelper;
    public $options = [];

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

    public $colors = ['red' , 'orange', 'green'];
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
                    $counts = $this->getCounts($responses, [$this->code]);
                    $map = $this->map ?? $this->getAnswers($this->code);
                    return $this->map($map, $counts);
                case 1:
                    // Ranking or multiple choice.
                    $titles = take(3, map(function (QuestionInterface $subQuestion) use ($question) {
                        return "{$question->getTitle()}[{$subQuestion->getTitle()}]";
                    }, $question->getQuestions(0)));
                    // Take the first to get the map.
                    $map = $this->map ?? $this->getAnswers($question->getTitle());
                    $counts = $this->getCounts($responses, $titles);
                    return $this->map($map, $counts);
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

            if (isset($this->map)) {
                return $this->map($this->map, $counts);
            }

            ksort($counts);
            return $counts;
        }




    }

    private function map(array $map, array $counts)
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
        return $result;
    }

    public function run()
    {
        $this->registerClientScript();

        $options = $this->options;
        Html::addCssClass($options, strtr(__CLASS__, ['\\' => '_']));
        $options['id'] = $this->getId();

        echo Html::beginTag('div', $options);

        $dataSet = $this->getDataSet($this->data);
        $count = count($dataSet);
        if ($count > 30) {
            $this->type = self::TYPE_BAR;
        }
        $baseColors = Json::encode($this->colors);
        $config = [
            'type' => $this->type,
            'data' => [
                'datasets' => [
                    [
                        'data' => array_values($dataSet),
                        'backgroundColor' => new JsExpression("chroma.scale($baseColors).colors($count)")
                    ]
                ],
                'labels' => array_keys($dataSet)
            ],
            'options' => [
                'scales' => [
                    'xAxes' => [
                        [
                            'display' => false,
                        ]
                    ]
                ],
                'elements' => [
                    'center' => [
                        'text' => array_sum($dataSet)
                    ]
                ],
                'legend' => [
                    'position' => 'bottom',
                    'display' => $this->type === self::TYPE_DOUGHNUT
                ],
                'cutoutPercentage' => 80,

                'title' => [
                    'display' => $this->type === self::TYPE_DOUGHNUT,
                    'text' => $this->title ?? $this->getTitle()
                ],
                'responsive' => true,
                'maintainAspectRatio' => false
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
        echo Html::endTag('div');
    }

    protected function registerClientScript()
    {
        $js = <<<JS
Chart.pluginService.register({
        beforeDraw: function (chart) {
            if (chart.config.options.elements.center) {
                //Get ctx from string
                var ctx = chart.chart.ctx;

                //Get options from the center object in options
                var centerConfig = chart.config.options.elements.center;
                var fontStyle = centerConfig.fontStyle || 'Arial';
                var txt = centerConfig.text;
                var color = centerConfig.color || '#000';
                var sidePadding = centerConfig.sidePadding || 20;
                var sidePaddingCalculated = (sidePadding/100) * (chart.innerRadius * 2)
                //Start with a base font of 30px
                ctx.font = "30px " + fontStyle;

                //Get the width of the string and also the width of the element minus 10 to give it 5px side padding
                var stringWidth = ctx.measureText(txt).width;
                var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

                // Find out how much the font can grow in width.
                var widthRatio = elementWidth / stringWidth;
                var newFontSize = Math.floor(30 * widthRatio);
                var elementHeight = (chart.innerRadius * 2);

                // Pick a new font size so it will not be larger than the height of label.
                var fontSizeToUse = Math.min(newFontSize, elementHeight);

                //Set font settings to draw it correctly.
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
                var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
                ctx.font = fontSizeToUse+"px " + fontStyle;
                ctx.fillStyle = color;

                //Draw text in center
                ctx.fillText(txt, centerX, centerY);
            }
        }
    });
JS;

        $this->view->registerAssetBundle(ChartJsBundle::class);
        $this->view->registerJs($js, View::POS_END);



    }


}
