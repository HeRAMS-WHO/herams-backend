<?php

declare(strict_types=1);

namespace prime\models\project;

use prime\objects\Locale;

class ProjectLocales
{
    /**
     * @param list<Locale> $locales
     */
    public function __construct(private array $locales = [])
    {
    }

    /**
     * @return list<Locale>
     */
    public function getLocales(): array
    {
        return $this->locales;
    }
}
