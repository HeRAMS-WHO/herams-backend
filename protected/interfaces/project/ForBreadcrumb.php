<?php
declare(strict_types=1);

namespace prime\interfaces\project;

use prime\objects\LanguageSet;
use prime\values\ProjectId;

interface ForBreadcrumb
{
    public function getId(): ProjectId;
    public function getLanguages(): LanguageSet;
    public function getTitle(): string;
}
