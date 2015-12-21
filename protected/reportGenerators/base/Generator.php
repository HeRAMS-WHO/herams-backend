<?php

namespace prime\reportGenerators\base;

use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\interfaces\UserDataInterface;
use prime\models\search\Project;
use prime\objects\ResponseCollection;
use SamIT\LimeSurvey\Interfaces\GroupInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\JsonRpc\Concrete\Question;
use yii\base\Component;
use yii\base\ViewContextInterface;
use yii\console\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

abstract class Generator extends Component implements ReportGeneratorInterface, ViewContextInterface
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
        return \Yii::t('app', 'base');
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
        return '';
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
        return '';
    }

    /**
     * Returns the title of the report
     * @return string
     */
    public function getReportTitle(ProjectInterface $project, SignatureInterface $signature)
    {
        return $this->title() . ' ' . $project->getLocality() . ' ' . $signature->getTime()->format('Y-m-d');
    }

    public function getQuestionAndAnswerTexts(SurveyCollectionInterface $surveys, $map)
    {
        $result = [];

        /** @var SurveyInterface $survey */
        foreach($surveys as $survey) {
            if(isset($map[$survey->getId()])) {
                $result[$survey->getId()] = [];
                $questionTitles = array_flip($map[$survey->getId()]);
                /** @var GroupInterface $group */
                foreach ($survey->getGroups() as $group) {
                    /** @var QuestionInterface $question */
                    /** @var Question $question */
                    foreach ($group->getQuestions() as $question) {
                        if(isset($questionTitles[$question->getTitle()])) {
                            $result[$survey->getId()][$question->getTitle()] = [
                                'text' => trim(html_entity_decode(strip_tags($question->getText()))),
                                'answers' => []
                            ];
                            $answers = $question->getAnswers();
                            if(isset($answers)) {
                                foreach($answers as $answer) {
                                    $result[$survey->getId()][$question->getTitle()]['answers'][$answer->getCode()] = $answer->getText();
                                }
                            }
                        }

                        if($question->getDimensions() == 1) {
                            $subQuestions = $question->getQuestions(0);
                            if (!empty($subQuestions)) {
                                foreach ($subQuestions as $subQuestion) {
                                    $key = $question->getTitle() . '[' . $subQuestion->getTitle() . ']';
                                    $result[$survey->getId()][$key] = [
                                        'text' => trim(html_entity_decode(strip_tags($question->getText()))),
                                        'answers' => []
                                    ];
                                    $answers = $subQuestion->getAnswers();
                                    if (isset($answers)) {
                                        foreach ($answers as $answer) {
                                            $result[$survey->getId()][$key]['answers'][$answer->getCode(
                                            )] = $answer->getText();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $result;
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

    public static function textarea(UserDataInterface $userData, $attribute, $options = [])
    {
        return Html::textarea($attribute, ArrayHelper::getValue($userData, $attribute, null), $options);
    }
}