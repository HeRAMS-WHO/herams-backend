<?php

namespace prime\reportGenerators\ccpm;

use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\interfaces\UserDataInterface;
use SamIT\LimeSurvey\Interfaces\GroupInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Component;
use yii\base\ViewContextInterface;
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
        return $this->view->render('preview', ['userData' => $userData], $this);
    }

    /**
     * This function renders a report.
     * All responses to be used are given as 1 array of Response objects.
     * @param ResponseCollectionInterface $responses
     * @param SignatureInterface $signature
     * @param UserDataInterface|null $userData
     * @return ReportInterface
     */
    public function render(
        ResponseCollectionInterface $responses,
        SurveyCollectionInterface $surveys,
        SignatureInterface $signature,
        UserDataInterface $userData = null
    ) {
        return new Report($responses, $userData, $signature);
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
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__ . '/views/';
    }

    public function renderHeader()
    {
        return $this->view->render('header');
    }
}