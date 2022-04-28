<?php
declare(strict_types=1);

namespace prime\interfaces;

use Collecthor\DataInterfaces\RecordInterface;

/**
 * Extends the generic record interface with HeRAMS specific properties.
 * Note not each individual data / admin record needs to implement this; however each health facility MUST implement it.
 */
interface HeramsFacilityRecordInterface extends RecordInterface
{
    public function getLatitude(): ?float;
    public function getLongitude(): ?float;
}
