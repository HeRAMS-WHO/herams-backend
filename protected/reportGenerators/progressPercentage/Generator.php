<?php

namespace prime\reportGenerators\progressPercentage;

use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\UserDataInterface;
use yii\base\Component;
use yii\web\View;

class Generator extends Component implements ReportGeneratorInterface
{

    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title()
    {
        return \Yii::t('app', 'Percentage');
    }

    /**
     * @param ResponseCollectionInterface $responses
     * @param SignatureInterface $signature
     * @param UserDataInterface|null $userData
     * @return string
     */
    public function renderPreview(
        ResponseCollectionInterface $responses,
        SignatureInterface $signature = null,
        UserDataInterface $userData = null
    ) {
        return $this->render($responses, $signature, $userData);
    }

    /**
     * This function renders a report.
     * All responses to be used are given as 1 array of Response objects.
     * @param ResponseCollectionInterface $responseCollection
     * @param SignatureInterface $signature
     * @param UserDataInterface|null $userData
     * @return ReportInterface
     */
    public function render(
        ResponseCollectionInterface $responseCollection,
        SignatureInterface $signature = null,
        UserDataInterface $userData = null
    ) {
        return new Report($responseCollection, $signature, $userData);
    }
}