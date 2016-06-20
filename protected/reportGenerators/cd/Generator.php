<?php

namespace prime\reportGenerators\cd;

use prime\interfaces\ConfigurableGeneratorInterface;
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
    public $dateFormat = 'd F - Y';

    /**
     * Return answer to the question title in the response
     * @param $title
     * @return string|null
     */
    public function getQuestionValue($title)
    {
        return isset($this->response) && isset($this->response->getData()[$title]) ? $this->response->getData()[$title] : null;
    }

    /**
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__ . '/views/';
    }

    protected  function initResponses(ResponseCollectionInterface $responses)
    {
        $responses = $responses->sort(function(ResponseInterface $r1, ResponseInterface $r2) {
            // Reverse ordered
            return -1 * strcmp($r1->getId(), $r2->getId());
        });

        // Get the first element, we know the collection is traversable.
        foreach($responses as $key => $response) {
            $this->response = $response;
            break;
        }
    }

    public function mapWorkingModalities($value)
    {
        $map = [
            1 => \Yii::t('cd', 'Full-time'),
            2 => \Yii::t('cd', 'Part-time'),
            3 => \Yii::t('cd', 'Do not know'),
        ];

        return ArrayHelper::getValue($map, $value, '');
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
            2 => \Yii::t('cd', 'No'),
            3 => \Yii::t('cd', 'Do not know')
        ];
        //No isset check, if the the value is not set, either the wrong map, or the map is incomplete
        return ArrayHelper::getValue($map, $value, '');
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
        $this->initResponses($responses);
        $stream = \GuzzleHttp\Psr7\stream_for($this->view->render('publish', [
            'userData' => $userData,
            'signature' => $signature,
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