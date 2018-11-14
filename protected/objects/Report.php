<?php

namespace prime\objects;

use prime\interfaces\ReportInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\UserDataInterface;
use Psr\Http\Message\StreamInterface;

class Report implements ReportInterface
{
    protected $userData;
    protected $signature;
    protected $generator;
    protected $mimeType;
    protected $stream;
    protected $title;

    public function __construct(UserDataInterface $userData, SignatureInterface $signature, StreamInterface $stream, $generator, $title, $mimeType = 'text/html')
    {
        $this->userData = $userData;
        $this->signature = $signature;
        $this->generator = $generator;
        $this->stream = $stream;
        $this->mimeType = $mimeType;
        $this->title = $title;
    }

    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * Returns the Mime type of the body
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
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
        return $this->stream;
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
        return $this->title;
    }


}