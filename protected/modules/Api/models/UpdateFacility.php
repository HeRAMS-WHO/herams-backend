<?php

declare(strict_types=1);

namespace prime\modules\Api\models;

use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\DataInterfaces\VariableInterface;
use prime\attributes\SourcePath;
use prime\helpers\LocalizedString;
use prime\models\RequestModel;
use prime\models\ResponseModel;
use prime\values\FacilityId;
use prime\values\WorkspaceId;
use yii\validators\RequiredValidator;

final class UpdateFacility extends ResponseModel
{
    public LocalizedString|null $name = null;

    public RecordInterface|null $adminData = null;

    public function __construct(public readonly FacilityId $facilityId)
    {
        parent::__construct([]);
    }
}
