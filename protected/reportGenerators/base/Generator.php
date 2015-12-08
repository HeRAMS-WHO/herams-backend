<?php

namespace prime\reportGenerators\base;

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
use yii\helpers\Html;
use yii\web\View;

class Generator extends Component implements ReportGeneratorInterface, ViewContextInterface
{
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
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__ . '/views/';
    }

    public static function textarea(UserDataInterface $userData, $attribute, $options = [])
    {
        return Html::textarea($attribute, ArrayHelper::getValue($userData, $attribute, null), $options);
    }
}