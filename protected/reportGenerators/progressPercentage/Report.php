<?php

namespace prime\reportGenerators\progressPercentage;

use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\UserDataInterface;
use Psr\Http\Message\StreamInterface;
use yii\base\Component;

class Report extends Component implements ReportInterface
{
    protected $responseCollection;
    protected $signature;
    protected $userData;

    public function __construct(ResponseCollectionInterface $responseCollection, SignatureInterface $signature, UserDataInterface $userData = null)
    {
        $this->responseCollection = $responseCollection;
        $this->signature = $signature;
        $this->userData = $userData;
    }

    /**
     * Returns the body of the report (ie pdf or html)
     * @return StreamInterface
     */
    public function getStream()
    {
        return \GuzzleHttp\Psr7\stream_for(app()->getView()->render('@app/reportGenerators/progressPercentage/views/publish', ['report' => $this, 'userData' => $this->getUserData(), 'signature' => $this->getSignature()]));
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
        /* @todo implement stub */
        return 17;
    }

    public function getPartnersResponding()
    {
        /* @todo implement stub */
        return 10;
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
     * Returns all other (with respect to getSignatureData) the data the user entered when generating the report
     * @return UserDataInterface
     */
    public function getUserData()
    {
        return $this->userData;
    }
}