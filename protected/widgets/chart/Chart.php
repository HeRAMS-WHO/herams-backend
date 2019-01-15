<?php


namespace prime\widgets\chart;


use prime\traits\SurveyHelper;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;

class Chart extends Widget
{
    use SurveyHelper;
    public $options = [];
    public $data = [];
    public $code;

    /** @var SurveyInterface */
    public $survey;

    private function getPieDataSet(array $responses): array
    {
        // Check question type.
        $question = $this->findQuestionByCode($this->code);
        $map = $this->getAnswers($this->code);
        ksort($map);
        $map[''] = 'No answer given';
        $counts = [];

        foreach($map as $k => $v) {
            $counts[$v] = 0;
        }

        foreach($responses as $response) {
            $value = $response->getValueForCode($question->getTitle()) ?? '';
            $counts[$map[$value]]++;
        }


        return $counts;
    }

    public function run()
    {
        $this->registerClientScript();

        $options = $this->options;
        Html::addCssClass($options, strtr(__CLASS__, ['\\' => '_']));
        $options['id'] = $this->getId();

        echo Html::beginTag('div', $options);

        $dataSet = $this->getPieDataSet($this->data);
        $count = count($dataSet);
        $config = [
            'type' => 'doughnut',
            'data' => [
                'datasets' => [
                    [
                        'data' => array_values($dataSet),
                        'backgroundColor' => new JsExpression("chroma.scale(['green', 'orange', 'red', 'grey']).colors($count)"),
                    ]
                ],
                'labels' => array_keys($dataSet)
            ],
            'options' => [
                'elements' => [
                    'center' => [
                        'text' => array_sum($dataSet)
                    ]
                ],
                'legend' => [
                    'position' => 'bottom',
                ],
                'cutoutPercentage' => 80,

                'title' => [
                    'display' => true,
                    'text' => $this->getTitle()
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