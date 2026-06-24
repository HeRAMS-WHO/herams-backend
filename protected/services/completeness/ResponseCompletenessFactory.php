<?php
declare(strict_types=1);

namespace prime\services\completeness;

final class ResponseCompletenessFactory
{
    /**
     * @param string|null $country ISO3166 alpha-3 code, as stored in Project::$country
     */
    public function __construct(private readonly ?string $country)
    {
    }

    public function create(): ResponseCompletenessInterface
    {
        return match (Country::tryFrom((string) $this->country)) {
            Country::MLI => new MaliResponseCompleteness(),
            Country::UKR => new UkraineResponseCompleteness(),
            default => new GenericResponseCompleteness(),
        };
    }
}
