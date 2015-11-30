<?php

namespace prime\interfaces;

use Psr\Http\Message\StreamInterface;

interface ReportGeneratorInterface {

    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title();

    /**
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return string
     */
    public function renderPreview(ResponseCollectionInterface $responses, SurveyCollectionInterface $surveys, SignatureInterface $signature, ProjectInterface $project, UserDataInterface $userData = null);

    /**
     * This function renders a report.
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return ReportInterface
     */
    public function render(ResponseCollectionInterface $responses, SurveyCollectionInterface $surveys, SignatureInterface $signature, ProjectInterface $project, UserDataInterface $userData = null);
}