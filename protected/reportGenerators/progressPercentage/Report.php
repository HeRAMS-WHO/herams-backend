<?php

namespace prime\reportGenerators\progressPercentage;

use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\UserDataInterface;
use Psr\Http\Message\StreamInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use yii\base\Component;

class Report extends Component implements ReportInterface
{
    protected $responseCollection;
    protected $signature;
    protected $userData;

    public function __construct(ResponseCollectionInterface $responseCollection, SignatureInterface $signature = null, UserDataInterface $userData = null)
    {
        parent::__construct();
        $this->responseCollection = $responseCollection;
        $this->signature = $signature;
        $this->userData = $userData;
    }

    public function getGenerator()
    {
        return 'progressPercentage';
    }

    /**
     * Returns the Mime type of the body
     * @return string
     */
    public function getMimeType()
    {
        return 'text/html';
    }

    public function getPartners()
    {
        return $this->responseCollection->size();
    }

    public function getPartnersResponding()
    {
        $result = 0;
        /** @var ResponseInterface $response */
        foreach($this->responseCollection as $response) {
            if (null !== $response->getSubmitDate()) {
                $result++;
            }
        }
        return $result;
    }

    public function getResponseRate()
    {
        return $this->getPartners() > 0 ?
            round(($this->getPartnersResponding() * 100) / $this->getPartners()) :
            0;
    }

    /**
     * Returns all the data that the user entered to "sign" the report when publishing.
     * @return SignatureInterface
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Returns the body of the report (ie pdf or html)
     * @return StreamInterface
     */
    public function getStream()
    {
        $content =
            app()->getView()->render(
                '@app/reportGenerators/progressPercentage/views/publish',
                [
                    'report' => $this,
                    'userData' => $this->getUserData(),
                    'signature' => $this->getSignature()
                ]
            );

        return \GuzzleHttp\Psr7\stream_for(
            app()->getView()->render(
                '@app/views/layouts/progressReport',
                [
                    'content' => $content
                ]
            )

        );
    }

    public function getTitle()
    {
        return 'Progress: Percentage';
    }

    /**
     * Returns all other (with respect to getSignatureData) the data the user entered when generating the report
     * @return UserDataInterface
     */
    public function getUserData()
    {
        return $this->userData;
    }
}