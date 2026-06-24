<?php
declare(strict_types=1);

namespace prime\services\completeness;

use prime\objects\HeramsSubject;

class GenericResponseCompleteness implements ResponseCompletenessInterface
{
    protected const AVAILABLE = HeramsSubject::FULLY_AVAILABLE;      // A1
    protected const PARTIAL   = HeramsSubject::PARTIALLY_AVAILABLE;  // A2
    protected const NOT_AVAIL = HeramsSubject::NOT_AVAILABLE;        // A3

    // Matches HeramsCodeMap::getSubjectExpression(); excludes the
    // "QHeRAMS<n>x" causes variants in both their nested-array and
    // flat "QHeRAMS<n>x[1]" key shapes.
    protected const SERVICE_KEY_PATTERN = '/^QHeRAMS\d+$/';

    public function isComplete(array $data): bool
    {
        $mosd4 = $this->scalar($data['MoSD4'] ?? null);

        // Clause 1: MoSD4 = A2 or A3.
        if (in_array($mosd4, [self::PARTIAL, self::NOT_AVAIL], true)) {
            return true;
        }

        // Everything below requires MoSD4 == A1.
        if ($mosd4 !== self::AVAILABLE) {
            return false;
        }

        $condb = $this->scalar($data['CONDB'] ?? null);

        // Clause 2: CONDB = A3.
        if ($condb === self::NOT_AVAIL) {
            return true;
        }

        // Clauses 3-5 require CONDB to be answered (non-empty).
        if ($this->isEmpty($condb)) {
            return false;
        }

        $hffunct = $this->scalar($data['HFFUNCT'] ?? null);

        // Clause 3: HFFUNCT = A3.
        if ($hffunct === self::NOT_AVAIL) {
            return true;
        }

        // Clauses 4-5 require HFFUNCT in (A1, A2).
        if (!in_array($hffunct, [self::AVAILABLE, self::PARTIAL], true)) {
            return false;
        }

        $hfacc = $this->scalar($data['HFACC'] ?? null);

        // Clause 4: HFACC = A3.
        if ($hfacc === self::NOT_AVAIL) {
            return true;
        }

        // Clause 5: HFACC in (A1, A2) and any QHeRAMS* in (A1, A2, A3).
        if (in_array($hfacc, [self::AVAILABLE, self::PARTIAL], true)) {
            return $this->hasAvailableService($data);
        }

        return false;
    }

    /**
     * True if any availability question (QHeRAMS<n>, not the "x" causes
     * variant) has a value of A1, A2 or A3.
     */
    protected function hasAvailableService(array $data): bool
    {
        foreach ($data as $code => $value) {
            if (!is_string($value)) {
                // Skips the nested "QHeRAMS<n>x" causes arrays and any group.
                continue;
            }
            if (!preg_match(self::SERVICE_KEY_PATTERN, (string) $code)) {
                continue;
            }
            if (in_array($value, [self::AVAILABLE, self::PARTIAL, self::NOT_AVAIL], true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Single-choice codes (MoSD4, CONDB, HFFUNCT, HFACC) are scalar strings;
     * an array here means a grouped value that cannot be a valid answer.
     */
    protected function scalar($value): ?string
    {
        if ($value === null || is_array($value)) {
            return null;
        }
        return (string) $value;
    }

    protected function isEmpty(?string $value): bool
    {
        return $value === null || $value === '';
    }
}
