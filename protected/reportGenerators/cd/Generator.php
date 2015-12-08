<?php

namespace prime\reportGenerators\cd;

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
use yii\helpers\ArrayHelper;
use yii\web\View;

class Generator extends \prime\reportGenerators\base\Generator
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
        return \Yii::t('app', 'CD');
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
        ProjectInterface $project,
        SignatureInterface $signature = null,
        UserDataInterface $userData = null
    ) {
        return new Report($responses, $userData, $signature, $this, $project);
    }

    /**
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__ . '/views/';
    }
}