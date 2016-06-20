<?php

namespace prime\reportGenerators\healthClusterDashboard;

use prime\interfaces\ProjectInterface;
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
    /** @var ResponseInterface */
    public $response;

     protected function initResponses(ResponseCollectionInterface $responses)
     {
        // Get the last element, we know the collection is traversable.
        foreach($responses as $key => $response) {
            $this->response = $response;
        }
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
            'responses' => $responses,
            'project' => $project,
            'generator' => $this
        ], $this));

        $userData = new UserData();
        return new Report($userData, $signature, $stream, $this->className(), $this->getReportTitle($project, $signature));
    }

    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title()
    {
        return \Yii::t('app', 'Health cluster dashboard');
    }
}