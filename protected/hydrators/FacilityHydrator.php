<?php

declare(strict_types=1);

namespace prime\hydrators;

use prime\attributes\SupportedType;
use prime\helpers\LocalizedString;
use prime\helpers\NormalizedArrayDataRecord;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\models\ActiveRecord;
use prime\models\ar\Facility;
use prime\models\RequestModel;
use prime\modules\Api\models\NewFacility;
use prime\modules\Api\models\UpdateFacility;

#[
    SupportedType(NewFacility::class, Facility::class),
    SupportedType(UpdateFacility::class, Facility::class)
]
class FacilityHydrator implements ActiveRecordHydratorInterface
{
    /**
     * @param NewFacility $source
     * @param Facility $target
     */
    public function hydrateActiveRecord(RequestModel $source, ActiveRecord $target): void
    {
        $i18n = $target->i18n;

        $i18n['name'] = $source->name;

        $target->i18n = $i18n;
        $target->name = $source->name->getDefault();

        if ($source instanceof NewFacility) {
            $target->workspace_id = $source->workspaceId->getValue();
        }
    }

    /**
     * @param UpdateFacility $target
     */
    public function hydrateRequestModel(ActiveRecord $source, RequestModel $target): void
    {
        assert($target instanceof UpdateFacility);
        assert($source instanceof Facility);

        $target->name = isset($source->i18n['name']) ? new LocalizedString($source->i18n['name']) : null;
        $target->adminData = isset($source->admin_data) ? new NormalizedArrayDataRecord($source->admin_data) : null;
    }
}
