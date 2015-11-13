<?php

namespace prime\reportGenerators\test;

use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\UserDataInterface;
use Psr\Http\Message\StreamInterface;
use yii\base\Component;

class Report extends Component implements ReportInterface
{
    protected $userData;
    protected $signature;

    public function __construct(ResponseCollectionInterface $responseCollection, UserDataInterface $userData, SignatureInterface $signature)
    {
        parent::__construct();
        $this->userData = $userData;
        $this->signature = $signature;
    }

    public function getGenerator()
    {
        return 'test';
    }

    /**
     * Returns the Mime type of the body
     * @return string
     */
    public function getMimeType()
    {
        return 'text/html';
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
        return \GuzzleHttp\Psr7\stream_for(app()->getView()->render('@app/reportGenerators/test/views/publish', ['userData' => $this->userData, 'signature' => $this->getSignature()]));
    }

    /**
     * Returns all other (with respect to getSignatureData) the data the user entered when generating the report
     * @return UserDataInterface
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * Returns the title of the report
     * @return string
     */
    public function getTitle()
    {
        return $this->userData->data['test']['title'];
    }


}