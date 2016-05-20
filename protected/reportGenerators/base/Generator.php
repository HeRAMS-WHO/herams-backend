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

    /**
     * @param ResponseInterface $response
     * @param array|string $code1 The question code containing the count.
     * @param array|string $code2 The question code containing the total.
     * @param mixed $default If not null then this is returned when total is 0, otherwise an exception is thrown.
     */
    public function getPercentage(ResponseInterface $response, $code1, $code2, $default = 0)
    {
        $data = $response->getData();
        $val1 = 0;
        $val2 = 0;
        foreach((array) $code1 as $code) {
            $val1 += $response->getData()[$code] ?: 0;
        }
        foreach((array) $code2 as $code) {
            $val2 += $response->getData()[$code] ?: 0;
        }

        if ($val2 > 0) {
            return round($val1 / $val2 * 100);
        } elseif (isset($default)) {
            return $default;
        } else {
            throw new \Exception("Can not calculate percentage when total is 0");
        }
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
        foreach($this->getGroupedQuestionValues($responses, $map, $inRangeValidator) as $surveyId => $responses) {
            foreach($responses as $rId => $rValues) {
                foreach($rValues as $qTitle => $qValue) {
                    $result[] = $qValue;
                }
            }
        }
        return $result;
    }

    public function getGroupedQuestionValues(ResponseCollectionInterface $responses, $map, $inRangeValidator = null)
    {
        $result = [];
        /** @var ResponseInterface $response */
        foreach($responses as $response) {
            $responseResult = [];
            //check if survey of response is in the requested map
            if(isset($map[$response->getSurveyId()])) {
                //for each of the requested question titles of the survey
                foreach($map[$response->getSurveyId()] as $title) {
                    //Check if the title isset in the response
                    if(isset($response->getData()[$title]) && null !== $response->getSubmitDate()) {
                        //Validate the result
                        if(!isset($inRangeValidator) || (isset($inRangeValidator) && is_callable($inRangeValidator) && $inRangeValidator($response->getData()[$title]))) {
                            $responseResult[$title] = $response->getData()[$title];
                        }
                    }
                }
            }
            if(!isset($result[$response->getSurveyId()])) {
                $result[$response->getSurveyId()] = [];
            }
            $result[$response->getSurveyId()][$response->getId()] = $responseResult;
        }
        return $result;
    }

    public function getViewPath()
    {
        $rc = new \ReflectionClass($this);
        return dirname($rc->getFileName()) . '/views';
    }

    public static function textarea(UserDataInterface $userData, $attribute, $options = [])
    {
        return Html::textarea($attribute, isset($userData[$attribute]) ? $userData[$attribute] : null, $options);
    }


}