<?php

declare(strict_types=1);

namespace herams\api\models;

use Collecthor\DataInterfaces\RecordInterface;
use prime\helpers\LocalizedString;
use prime\models\ResponseModel;
use prime\values\FacilityId;

final class UpdateFacility extends ResponseModel
{
    public LocalizedString|null $name = null;

    public RecordInterface|null $adminData = null;

    public function __construct(public readonly FacilityId $facilityId)
    {
        parent::__construct();
    }
}
