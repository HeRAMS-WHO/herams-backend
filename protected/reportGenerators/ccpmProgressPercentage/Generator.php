<?php

namespace prime\reportGenerators\ccpmProgressPercentage;

use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\interfaces\UserDataInterface;
use prime\models\ar\UserData;
use prime\objects\Report;
use yii\helpers\ArrayHelper;

class Generator extends \prime\reportGenerators\base\Generator
{
    public $CPASurveyId = 67825;
    public $PPASurveyId = 22814;

    /**
     * This function renders a report.
     * All responses to be used are given as 1 array of Response objects.
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return ReportInterface
     */
    public function render(
        ResponseCollectionInterface $responses,
        SurveyCollectionInterface $surveys,
        ProjectInterface $project,
        SignatureInterface $signature = null,
        UserDataInterface $userData = null
    ) {
        $limeSurvey = app()->limeSurvey;
        $stream = \GuzzleHttp\Psr7\stream_for($this->view->render('publish', [
            'userData' => $userData,
            'signature' => $signature,
            'responses' => $responses,
            'project' => $project,
            'surveys' => $surveys,
            'responseRates' => $this->getResponseRates($responses),
            'limeSurvey' => $limeSurvey
        ], $this));

        $userData = new UserData();
        return new Report($userData, $signature, $stream, $this->className(), $this->getReportTitle($project, $signature));
    }

    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title()
    {
        return \Yii::t('app', 'CCPM Percentage');
    }

    public function getResponseRates(ResponseCollectionInterface $responses)
    {
        $result = [];
        $responsesPerType = array_count_values($this->getQuestionValues($responses, [$this->PPASurveyId => ['q012']]));
        $responsesPerType['total'] = array_sum($responsesPerType);
        $totalsPerType = [
            1 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[1]']]), 0, 0),
            2 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[2]']]), 0, 0),
            3 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[3]']]), 0, 0),
            4 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[4]']]), 0, 0),
            5 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[5]']]), 0, 0),
            6 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[6]']]), 0, 0),
        ];
        $totalsPerType['total'] = array_sum($totalsPerType);

        $totalsPerType2 = [
            1 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[1]']]), 0, 0),
            2 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[2]']]), 0, 0),
            3 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[3]']]), 0, 0),
            4 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[4]']]), 0, 0),
            5 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[5]']]), 0, 0),
            6 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[6]']]), 0, 0),
        ];
        $totalsPerType2['total'] = array_sum($totalsPerType2);

        foreach ($totalsPerType as $number => $value) {
            $result[$number]['responses'] = ArrayHelper::getValue($responsesPerType, $number, 0);
            $result[$number]['total1'] = $totalsPerType[$number];
            $result[$number]['total2'] = $totalsPerType2[$number];
        }

        return $result;
    }





}