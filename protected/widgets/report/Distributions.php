<?php

namespace prime\widgets\report;

use app\components\Html;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

class Distributions extends Widget
{
    public $number;
    public $title;
    //Subsection to distributions map
    public $distributions = [];
    public $view;
    //question and answer texts
    public $questionsAndAnswers = [];
    //mapping of section to survey ids to question ids
    public $sectionQuestionMap = [];
    public $PPASurveyId;
    public $CPASurveyId;

    public function buildSeries($distribution, $section) {
        $temp = [];
        $surveys = array_flip(array_keys($distribution));

        //vdd($this->sectionQuestionMap);
        foreach($this->sectionQuestionMap[$section] as $surveyId => $questionTitles) {
            if($surveyId == $this->PPASurveyId) {
                foreach ($questionTitles as $questionTitle) {
                    foreach ($this->questionsAndAnswers[$surveyId][$questionTitle]['answers'] as $code => $text) {
                        $key = $code . '_' . $questionTitle . '_' . $surveyId;
                        $temp[$key] = [
                            'name' => $text,
                            'y' => ArrayHelper::getValue($distribution[$surveyId], $code, 0),
                            //'color' => new JsExpression('Highcharts.getOptions().colors[' . $surveys[$surveyId] . ']')
                        ];
                    }
                }

                ksort($temp);
            }
        }

        $result = [[
            'data' => array_values($temp),
            'dataLabels' => [
                'enabled' => false
            ],
            'animation' => false,
            'showInLegend' => true
        ]];
        return $result;
    }

    public function run()
    {
        $result = Html::beginTag('div', ['class' => 'row']);

        //Title
        $result .= Html::tag('h4', $this->number . ' ' . $this->title, ['class' => 'col-xs-12']);

        //Columns
        $items = [];
        foreach($this->distributions as $subTitle => $distribution)
        {
            $section = $this->number . '.' . (count($items) + 1);
            $title = $section . ' ' . $subTitle;

            //Find CPA question and answer
            $CQuestion = '';
            $CAnswer = '';
            if(isset($distribution[$this->CPASurveyId]) && !empty($distribution[$this->CPASurveyId])) {
                $question = $this->sectionQuestionMap[$section][$this->CPASurveyId][0];
                $CQuestion = $this->questionsAndAnswers[$this->CPASurveyId][$question]['text'];
                $CAnswer = $this->questionsAndAnswers[$this->CPASurveyId][$question]['answers'][array_keys($distribution[$this->CPASurveyId])[0]];
            }

            $PQuestion = '';
            if(isset($distribution[$this->PPASurveyId]) && !empty($distribution[$this->PPASurveyId])) {
                $question = $this->sectionQuestionMap[$section][$this->PPASurveyId][0];
                $PQuestion = $this->questionsAndAnswers[$this->CPASurveyId][$question]['text'];
            }

            $items[] = [
                'content' => $this->render('distributionGraph', [
                    'title' => $title,
                    'distribution' => $distribution,
                    'view' => $this->view,
                    'series' => $this->buildSeries($distribution, $section),
                    'CAnswer' => $CAnswer,
                    'CQuestion' => $CQuestion,
                    'PQuestion' => $PQuestion
                ])
            ];
        }

        $result .= Html::beginTag('div', ['class' => 'col-xs-12']);
        $result .= Columns::widget([
            'items' => $items,
            'columnsInRow' => 1
        ]);
        $result .= Html::endTag('div');

        $result .= Html::endTag('div');
        return $result;
    }


}