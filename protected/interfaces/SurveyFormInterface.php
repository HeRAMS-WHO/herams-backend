<?php

declare(strict_types=1);

namespace prime\interfaces;

use Psr\Http\Message\UriInterface;

interface SurveyFormInterface
{
    public function getSubmitRoute(): UriInterface;

    public function getServerValidationRoute(): UriInterface;

    public function getRedirectRoute(): null|UriInterface;

    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(): array;

    public function getDataRoute(): null|UriInterface;

    public function getExtraData(): array;

    public function getLocaleEndpoint(): null|UriInterface;
}
