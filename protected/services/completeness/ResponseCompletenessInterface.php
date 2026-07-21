<?php
declare(strict_types=1);

namespace prime\services\completeness;

interface ResponseCompletenessInterface
{
    /**
     * @param array $data the raw response data (contents of the `data` JSON column)
     * @param string|null $date the response `date` column (stored outside the JSON data)
     */
    public function isComplete(array $data, ?string $date): bool;
}
