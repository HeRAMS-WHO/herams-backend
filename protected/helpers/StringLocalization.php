<?php

declare(strict_types=1);

namespace prime\helpers;

use prime\objects\enums\Language;

/**
 * Models a specific localization of a string
 */
class StringLocalization
{
    public function __construct(
        public readonly Language $language,
        public readonly string $value
    ) {
    }
}
