<?php

namespace prime\interfaces;

use Psr\Http\Message\StreamInterface;

interface ReportGeneratorInterface {

    /**
     * @param ResponseCollectionInterface $responses
     * @param UserDataInterface|null $userData
     * @return mixed
     */
    public function renderPreview(ResponseCollectionInterface $responses, UserDataInterface $userData = null);

    /**
     * This function renders a report.
     * All responses to be used are given as 1 array of Response objects.
     * @param ResponseCollectionInterface $responses
     * @param SignatureInterface $signature
     * @param UserDataInterface|null $userData
     * @return ReportInterface
     */
    public function render(ResponseCollectionInterface $responses, SignatureInterface $signature, UserDataInterface $userData = null);
}