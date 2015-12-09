<?php

namespace prime\reportGenerators\ccpm;

use prime\factories\GeneratorFactory;
use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\interfaces\UserDataInterface;
use prime\objects\Report;
use yii\helpers\ArrayHelper;

class Generator extends \prime\reportGenerators\base\Generator
{

    public function calculateScore(ResponseCollectionInterface $responses, $map, $method = 'median')
    {
        $result = $this->getQuestionValues($responses, $map, [$this, 'rangeValidator04']);
        if (!empty($result)) {
            switch ($method) {
                case 'average':
                    $result = average($result);
                    break;
                case 'median':
                    $result = median($result);
                    break;
            }

            return $this->map04($result);
        }
    }

    public function getResponseRates(ResponseCollectionInterface $responses)
    {
        $result = [];
        $responsesPerType = array_count_values($this->getQuestionValues($responses, [22814 => ['q012']]));
        $responsesPerType['total'] = array_sum($responsesPerType);
        $totalsPerType = [
            1 => (int)$this->getQuestionValues($responses, [67825 => ['q012[1]']])[0],
            2 => (int)$this->getQuestionValues($responses, [67825 => ['q012[2]']])[0],
            3 => (int)$this->getQuestionValues($responses, [67825 => ['q012[3]']])[0],
            4 => (int)$this->getQuestionValues($responses, [67825 => ['q012[4]']])[0],
            5 => (int)$this->getQuestionValues($responses, [67825 => ['q012[5]']])[0],
            6 => (int)$this->getQuestionValues($responses, [67825 => ['q012[6]']])[0],
        ];
        $totalsPerType['total'] = array_sum($totalsPerType);

        $totalsPerType2 = [
            1 => (int)$this->getQuestionValues($responses, [67825 => ['q013[1]']])[0],
            2 => (int)$this->getQuestionValues($responses, [67825 => ['q013[2]']])[0],
            3 => (int)$this->getQuestionValues($responses, [67825 => ['q013[3]']])[0],
            4 => (int)$this->getQuestionValues($responses, [67825 => ['q013[4]']])[0],
            5 => (int)$this->getQuestionValues($responses, [67825 => ['q013[5]']])[0],
            6 => (int)$this->getQuestionValues($responses, [67825 => ['q013[6]']])[0],
        ];
        $totalsPerType2['total'] = array_sum($totalsPerType2);

        foreach ($totalsPerType as $number => $value) {
            $result[$number]['responses'] = ArrayHelper::getValue($responsesPerType, $number, 0);
            $result[$number]['total1'] = $totalsPerType[$number];
            $result[$number]['total2'] = $totalsPerType2[$number];
        }

        return $result;
    }

    /**
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__ . '/views/';
    }

    public function map04($value)
    {
        return $value * 25;
    }

    public function mapStatus($value)
    {
        $map = [
            25 => 'weak',
            50 => 'unsatisfactory',
            75 => 'satisfactory',
            100 => 'good'
        ];
        foreach($map as $max => $status) {
            if ($value <= $max) {
                return $status;
            }
        }
        return $status;
    }

    protected function rangeValidator04($value)
    {
        return $value >= 0 && $value <= 4;
    }

    /**
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys,
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return string
     */
    public function renderPreview(
        ResponseCollectionInterface $responses,
        SurveyCollectionInterface $surveys,
        ProjectInterface $project,
        SignatureInterface $signature = null,
        UserDataInterface $userData = null
    ) {
        return $this->view->render('preview', [
            'userData' => $userData,
            'project' => $project,
            'signature' => $signature,
            'responses' => $responses
        ], $this);
    }

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
        $stream = \GuzzleHttp\Psr7\stream_for($this->view->render('publish', [
            'userData' => $userData,
            'signature' => $signature,
            'responses' => $responses,
            'project' => $project
        ], $this));

        return new Report($userData, $signature, $stream, __CLASS__, $this->getReportTitle($project, $signature));
    }

    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title()
    {
        return \Yii::t('ccpm', 'CCPM');
    }
}