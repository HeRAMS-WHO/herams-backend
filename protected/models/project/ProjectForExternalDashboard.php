<?php

declare(strict_types=1);

namespace prime\models\project;

class ProjectForExternalDashboard
{
    public function __construct(
        private string $title,
        private string $externalUrl
    ) {
    }
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getExternalUrl(): string
    {
        return $this->externalUrl;
    }
}
