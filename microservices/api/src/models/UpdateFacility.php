<?php

declare(strict_types=1);

namespace herams\api\models;

use Collecthor\DataInterfaces\RecordInterface;
use herams\common\helpers\LocalizedString;
use herams\common\models\ResponseModel;
use herams\common\values\FacilityId;

final class UpdateFacility extends ResponseModel
{
    public LocalizedString|null $name = null;

    public RecordInterface|null $adminData = null;

    public function __construct(public readonly FacilityId $facilityId)
    {
        parent::__construct();
    }
}
