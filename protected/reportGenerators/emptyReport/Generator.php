<?php

namespace prime\reportGenerators\emptyReport;

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
    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title()
    {
        return \Yii::t('app', 'Empty report');
    }

    public function render(
        ResponseCollectionInterface $responses,
        SurveyCollectionInterface $surveys,
        ProjectInterface $project,
        SignatureInterface $signature = null,
        UserDataInterface $userData = null
    ) {
        $stream = \GuzzleHttp\Psr7\stream_for('');
        $userData = new UserData();
        return new Report($userData, $signature, $stream, $this->className(), $this->getReportTitle($project, $signature));
    }


}