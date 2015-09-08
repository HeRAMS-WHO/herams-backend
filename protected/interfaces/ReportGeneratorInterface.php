<?php

namespace prime\interfaces;

use Psr\Http\Message\StreamInterface;

interface ReportGeneratorInterface {

    /**
     * This function renders a report.
     * All responses to be used are given as 1 array of Response objects.
     * @param ResponseInterface[] $responses
     * @param SurveyInterface[] $survey
     * @return ReportInterface
     */
    public function render($responses, $surveys, \JsonSerializable $userData = null);
}