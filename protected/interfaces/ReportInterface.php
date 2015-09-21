<?php
namespace prime\interfaces;

use Psr\Http\Message\StreamInterface;

interface ReportInterface {

    /**
     * Returns the body of the report (ie pdf or html)
     * @return StreamInterface
     */
    public function getStream();

    /**
     * Returns the Mime type of the body
     * @return string
     */
    public function getMimeType();

    /**
     * Returns all the data that the user entered to "sign" the report when publishing.
     * @return \JsonSerializable
     */
    public function getSignatureData();

    /**
     * Returns all other (with respect to getSignatureData) the data the user entered when generating the report
     * @return \JsonSerializable
     */
    public function getUserData();
}