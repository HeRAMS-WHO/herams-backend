<?php

namespace prime\reportGenerators\ccpm;

use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\UserDataInterface;
use Psr\Http\Message\StreamInterface;
use yii\base\Component;

class Report extends Component implements ReportInterface
{
    protected $responses;
    protected $userData;
    protected $signature;
    protected $generator;
    protected $project;

    public function __construct(ResponseCollectionInterface $responses, UserDataInterface $userData, SignatureInterface $signature, Generator $generator, ProjectInterface $project)
    {
        parent::__construct();
        $this->userData = $userData;
        $this->signature = $signature;
        $this->generator = $generator;
        $this->responses = $responses;
        $this->project = $project;
    }

    public function getGenerator()
    {
        return 'ccpm';
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
        return \GuzzleHttp\Psr7\stream_for(app()->getView()->render('publish', [
            'userData' => $this->userData,
            'signature' => $this->getSignature(),
            'responses' => $this->responses,
            'project' => $this->project
        ], $this->generator));
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
        return 'CCPM';
    }


}