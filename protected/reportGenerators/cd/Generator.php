<?php

namespace prime\reportGenerators\cd;

use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\interfaces\UserDataInterface;
use prime\objects\Report;
use prime\objects\ResponseCollection;
use SamIT\LimeSurvey\Interfaces\GroupInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Component;
use yii\base\ViewContextInterface;
use yii\console\Exception;
use yii\helpers\ArrayHelper;
use yii\web\View;

class Generator extends \prime\reportGenerators\base\Generator
{
    /** @var ResponseInterface */
    protected $response;
    public $dateFormat = 'd F - Y';

    /**
     * Return answer to the question title in the response
     * @param $title
     * @return string|null
     */
    public function getQuestionValue($title)
    {
//        $responses = new ResponseCollection();
//        $responses->append($this->response);
//        $values = $this->getQuestionValues($responses, [$this->response->getSurveyId() => $title]);
        return isset($this->response->getData()[$title]) ? $this->response->getData()[$title] : null;
    }

    /**
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__ . '/views/';
    }

    public function mapWorkingModalities($value)
    {
        $map = [
            1 => \Yii::t('cd', 'Full-time'),
            2 => \Yii::t('cd', 'Part-time'),
            3 => \Yii::t('cd', 'Do not know'),
        ];
        //No isset check, if the the value is not set, either the wrong map, or the map is incomplete
        return $map[$value];
    }

    public function mapYesNo($value)
    {
        //there are also some string mappings
        if($value == '') {
            $value = 2;
        } elseif ($value == 'Y') {
            $value = 1;
        }

        $map = [
            1 => \Yii::t('cd', 'Yes'),
            2 => \Yii::t('cd', 'No')
        ];
        //No isset check, if the the value is not set, either the wrong map, or the map is incomplete
        return $map[$value];
    }

    /**
     * @param ResponseCollectionInterface $responses
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
        //vdd($this->mapStatus($this->map04(median($this->getQuestionValues($responses, [67825 => ['q112'], 22814 => ['q111']], [$this, 'rangeValidator04'])))));
        $this->response = $responses[0];
        return $this->view->render('preview', ['userData' => $userData, 'project' => $project, 'signature' => $signature], $this);
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
        return \Yii::t('cd', 'CD');
    }
}