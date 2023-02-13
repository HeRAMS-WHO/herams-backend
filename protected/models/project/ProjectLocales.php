<?php

declare(strict_types=1);

namespace prime\models\project;

use herams\common\helpers\Locale;

class ProjectLocales
{
    /**
     * @var list<Locale>
     */
    private readonly array $locales;

    public function __construct(Locale ...$locales)
    {
        $this->locales = array_values($locales);
    }

    /**
     * @return list<Locale>
     */
    public function getLocales(): array
    {
        return $this->locales;
    }
}
