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
use yii\base\ViewEvent;
use yii\console\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

abstract class Generator extends Component implements ReportGeneratorInterface, ViewContextInterface
{
    /** @var ResponseInterface */
    protected $response;

    protected $view;

    /**
     * @var boolean[] Whether the current block should be outputted.
     */
    protected $blocks = [];
    public function __construct(View $view, array $config = [])
    {
        parent::__construct($config);
        $this->view = $view;

        $counts = [];
        $view->on($view::EVENT_BEFORE_RENDER, function(ViewEvent $event) use (&$counts) {
            $counts[] = count($this->blocks);

        });
        $view->on($view::EVENT_AFTER_RENDER, function(ViewEvent $event) use (&$counts) {
            if (count($this->blocks) != array_pop($counts)) {
                throw new \Exception("Not all blocks are closed in " . $event->viewFile);
            }
        });
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
     * Returns the title of the report
     * @return string
     */
    public function getReportTitle(ProjectInterface $project, SignatureInterface $signature)
    {
        return $this->title() . ' ' . $project->getLocality() . ' ' . $signature->getTime()->format('Y-m-d');
    }

    /**
     * @param array|string $code1 The question code containing the count.
     * @param array|string $code2 The question code containing the total.
     * @param mixed $default If not null then this is returned when total is 0, otherwise an exception is thrown.
     * @return float|int|mixed
     * @throws \Exception
     */
    public function getPercentage($code1, $code2, $default = 0)
    {
        $val1 = 0;
        $val2 = 0;
        foreach((array) $code1 as $code) {
            $val1 += $this->getQuestionValue($code) ?: 0;
        }
        foreach((array) $code2 as $code) {
            $val2 += $this->getQuestionValue($code) ?: 0;
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
        if (!empty($result)) {
            $this->markBlock();
        }
        return $result;
    }

    /**
     * Return answer to the question title in the response
     * @param $title
     * @return string|null
     */
    public function getQuestionValue($title)
    {
        if (isset($this->response)
            && [] !== ($data = $this->response->getData())
            && isset($data[$title])
            && !empty($data[$title]))
        {

            $this->markBlock();
            return $data[$title];
        }
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
        if (!empty($result)) {
            $this->markBlock();
        }
        return $result;
    }

    public function getViewPath()
    {
        return dirname((new \ReflectionClass($this))->getFileName()) . '/views';
    }

    public static function textarea(UserDataInterface $userData, $attribute, $options = [])
    {
        return Html::textarea($attribute, isset($userData[$attribute]) ? $userData[$attribute] : null, $options);
    }



    /**
     * Begins a block that will only be rendered if at least one of the variables it uses is not empty.
     */
    public function beginBlock()
    {
        $this->blocks[] = false;
        ob_start();
    }

    /**
     * Marks the current block, if any, to be outputted.
     */
    public function markBlock()
    {
        if (!empty($this->blocks)) {
            array_pop($this->blocks);
            $this->blocks[] = true;
        }
    }

    public function block(\Closure $closure)
    {
        $this->beginBlock();
        $closure();
        $this->endBlock();
    }
    /**
     * Ends the current block and outputs it if one of the variables was not empty.
     */
    public function endBlock()
    {
        if (array_pop($this->blocks)) {
            ob_end_flush();
            // Since we just outputted the block, we should mark the parent block as needing outputting as well.
            $this->markBlock();

        } else {
            // For debugging output it anyway.
            ob_end_clean();
        }

    }

}