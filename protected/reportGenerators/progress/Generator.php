<?php

namespace prime\reportGenerators\progress;

use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\interfaces\UserDataInterface;
use prime\models\ar\UserData;
use prime\objects\Report;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;

class Generator extends \prime\reportGenerators\base\Generator
{
    public $dateFormat = 'd F - Y';

    /**
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__ . '/views/';
    }

    /**
     * This function renders a report.
     * All responses to be used are given as 1 array of Response objects.
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys
     * @param ProjectInterface $project
     * @param SignatureInterface $signature
     * @param UserDataInterface|null $userData
     * @return ReportInterface
     */
    public function render(
        ResponseCollectionInterface $responses,
        SurveyCollectionInterface $surveys,
        ProjectInterface $project,
        SignatureInterface $signature,
        UserDataInterface $userData
    ) {
        $stream = \GuzzleHttp\Psr7\stream_for($this->view->render('publish', [
            'userData' => $userData,
            'signature' => $signature,
            'responses' => $responses,
            'project' => $project,
            'surveys' => $surveys
        ], $this));

        $userData = new UserData();
        return new Report($userData, $signature, $stream, $this->className(), $this->getReportTitle($project, $signature));
    }

    /**
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return string
     */
//    public function renderPreview(
//        ResponseCollectionInterface $responses,
//        SurveyCollectionInterface $surveys,
//        ProjectInterface $project,
//        SignatureInterface $signature = null,
//        UserDataInterface $userData = null
//    ) {
//        return $this->view->render('publish', [
//            'progresses' => $this->getProgresses($responses)
//        ], $this);
//    }

    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title()
    {
        return \Yii::t('app', 'General progress');
    }





}