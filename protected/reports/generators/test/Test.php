<?php

namespace prime\reports\generators;

use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\UserDataInterface;
use yii\base\Component;
use yii\web\View;

class Test extends Component implements ReportGeneratorInterface
{

    /**
     * Returns the title of the Report
     * @return string
     */
    public function getTitle()
    {
        return \Yii::t('app', 'Test generator');
    }

    /**
     * @param ResponseCollectionInterface $responses
     * @param SignatureInterface $signature
     * @param UserDataInterface|null $userData
     * @return string
     */
    public function renderPreview(
        ResponseCollectionInterface $responses,
        SignatureInterface $signature,
        UserDataInterface $userData = null
    ) {
        return app()->getView()->render('@app/reports/generators/test/views/preview', ['userData' => $userData]);
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
        SignatureInterface $signature,
        UserDataInterface $userData = null
    ) {
        // TODO: Implement render() method.
    }
}