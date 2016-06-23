<?php

namespace prime\interfaces;

use Psr\Http\Message\StreamInterface;

interface ConfigurableGeneratorInterface extends ReportGeneratorInterface
{
    /**
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return string
     */
    public function renderConfiguration(ResponseCollectionInterface $responses, SurveyCollectionInterface $surveys, ProjectInterface $project, SignatureInterface $signature = null, UserDataInterface $userData = null);
}