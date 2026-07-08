<?php
declare(strict_types=1);

namespace prime\services\completeness;

final class UkraineResponseCompleteness extends GenericResponseCompleteness
{
    /**
     * When GEO8 is A2 or A3 the questionnaire stops before the services
     * section, so completeness must not require an answered service.
     */
    protected function requiresServiceEvaluation(array $data): bool
    {
        return !in_array($this->scalar($data['GEO8'] ?? null), ['A2', 'A3'], true);
    }
}
