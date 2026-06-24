<?php
declare(strict_types=1);

namespace prime\services\completeness;

interface ResponseCompletenessInterface
{
    /**
     * @param array $data the raw response data (contents of the `data` JSON column)
     */
    public function isComplete(array $data): bool;
}
