<?php

namespace prime\interfaces;

use Psr\Http\Message\StreamInterface;

interface ReportGeneratorInterface {

    /**
     * This function renders a report.
     * All responses to be used are given as 1 array of Response objects.
     * @param ResponseInterface[] $responses
     * @return ReportInterface
     */
    public function render($responses, \JsonSerializable $userData = null);
}