<?php

namespace prime\reportGenerators\ccpm;

use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\interfaces\UserDataInterface;
use prime\objects\ResponseCollection;
use SamIT\LimeSurvey\Interfaces\GroupInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Component;
use yii\base\ViewContextInterface;
use yii\console\Exception;
use yii\web\View;

class Generator extends Component implements ReportGeneratorInterface, ViewContextInterface
{
    protected $view;

    public function __construct(View $view, array $config = [])
    {
        parent::__construct($config);
        $this->view = $view;
    }

    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title()
    {
        return \Yii::t('app', 'CCPM');
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
        SignatureInterface $signature,
        ProjectInterface $project,
        UserDataInterface $userData = null
    ) {
        //vdd($this->mapStatus($this->map04(median($this->getQuestionValues($responses, [67825 => ['q112'], 22814 => ['q111']], [$this, 'rangeValidator04'])))));
        return $this->view->render('preview', ['userData' => $userData, 'project' => $project, 'signature' => $signature, 'responses' => $responses], $this);
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
        SignatureInterface $signature,
        ProjectInterface $project,
        UserDataInterface $userData = null
    ) {
        return new Report($responses, $userData, $signature, $this, $project);
    }

    public function getQuestionText(SurveyCollectionInterface $surveys, $title, $surveyId)
    {
        /** @var SurveyInterface $survey */
        foreach($surveys as $survey) {
            if($survey->getId() == $surveyId) {
                /** @var GroupInterface $group */
                foreach ($survey->getGroups() as $group) {
                    /** @var QuestionInterface $question */
                    foreach ($group->getQuestions() as $question) {
                        if ($question->getTitle() == $title) {
                            return $question->getText();
                        }
                    }
                }
            }
        }
    }

    /**
     * Returns all values that follow the map and are valid
     * map should be of form [surveyId => [title, title title], ...]
     * @param ResponseCollectionInterface $responses
     * @param $map array
     * @param null $inRangeValidator
     * @return array
     */
    public function getQuestionValues(ResponseCollectionInterface $responses, $map, $inRangeValidator = null)
    {
        $result = [];
        /** @var ResponseInterface $response */
        foreach($responses as $response) {
            //check if survey of response is in the requested map
            if(isset($map[$response->getSurveyId()])) {
                //for each of the requested question titles of the survey
                foreach($map[$response->getSurveyId()] as $title) {
                    //Check if the title isset in the response
                    if(isset($response->getData()[$title]) && null !== $response->getSubmitDate()) {
                        //Validate the result
                        if(!isset($inRangeValidator) || (isset($inRangeValidator) && is_callable($inRangeValidator) && $inRangeValidator($response->getData()[$title]))) {
                            $result[] = $response->getData()[$title];
                        }
                    }
                }
            }
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

    public function calculateScore(ResponseCollectionInterface $responses, $map, $method = 'median')
    {
        $result = $this->getQuestionValues($responses, $map, [$this, 'rangeValidator04']);
        switch($method) {
            case 'average':
                $result = average($result);
                break;
            case 'median':
                $result = median($result);
                break;
        }
        return $this->map04($result);
    }

    protected function rangeValidator04($value)
    {
        return $value >= 0 && $value <= 4;
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
}