<?php
declare(strict_types=1);

namespace prime\services\completeness;

/**
 * Kill switch for the response-completeness feature.
 */
final class CompletenessFeature
{
    /**
     * Project id the feature is enabled for. Set to 0 (a non-existent id) to
     * disable the feature for every project.
     */
    public const PROJECT_ID = 77; // staging - HeRAMS Training - project

    public static function isEnabledFor(?int $projectId): bool
    {
        return $projectId !== null && $projectId === self::PROJECT_ID;
    }
}
