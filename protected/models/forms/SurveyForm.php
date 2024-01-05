<?php

declare(strict_types=1);

namespace prime\models\forms;

use prime\interfaces\SurveyFormInterface;
use Psr\Http\Message\UriInterface;

class SurveyForm implements SurveyFormInterface
{
    public function __construct(
        private UriInterface $submitRoute,
        private UriInterface $serverValidationRoute,
        private array $configuration,
        private null|UriInterface|string $redirectRoute = null,
        private null|UriInterface $dataRoute = null,
        private null|UriInterface $localeEndpoint = null,
        private array $extraData = [],
    ) {
    }

    public function getSubmitRoute(): UriInterface
    {
        return $this->submitRoute;
    }

    public function getServerValidationRoute(): UriInterface
    {
        return $this->serverValidationRoute;
    }

    public function getRedirectRoute(): null|UriInterface|string
    {
        return $this->redirectRoute;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function getDataRoute(): null|UriInterface
    {
        return $this->dataRoute;
    }

    public function getLocaleEndpoint(): null|UriInterface
    {
        return $this->localeEndpoint;
    }

    public function getExtraData(): array
    {
        return $this->extraData;
    }
}
