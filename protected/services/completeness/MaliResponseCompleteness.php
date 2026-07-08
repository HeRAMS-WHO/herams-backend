<?php
declare(strict_types=1);

namespace prime\services\completeness;

final class MaliResponseCompleteness extends GenericResponseCompleteness
{
    /**
     * CHWs (MoSD3 = A11) have a reduced questionnaire without the CONDB and
     * HFFUNCT questions; evaluation jumps from MoSD4 straight to HFACC.
     */
    protected function skipsBuildingConditionAndFunctionality(array $data): bool
    {
        return $this->scalar($data['MoSD3'] ?? null) === 'A11';
    }
}
