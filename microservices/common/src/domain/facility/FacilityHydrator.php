<?php

declare(strict_types=1);

namespace herams\common\domain\facility;

use herams\common\attributes\SupportedType;
use herams\common\helpers\LocalizedString;
use herams\common\helpers\NormalizedArrayDataRecord;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\models\ActiveRecord;
use herams\common\models\RequestModel;
use yii\base\NotSupportedException;

#[
    SupportedType(NewFacility::class, Facility::class),
]
class FacilityHydrator implements ActiveRecordHydratorInterface
{
    /**
     * @param NewFacility $source
     * @param FacilityRead $target
     */
    public function hydrateActiveRecord(RequestModel $source, ActiveRecord $target): void
    {
        if (! $target instanceof Facility) {
            throw new \InvalidArgumentException();
        }

        if ($source instanceof NewFacility) {
            $target->admin_data = $source->data->allData();
            $target->workspace_id = $source->workspaceId->getValue();
        }
    }

    public function hydrateRequestModel(ActiveRecord $source, RequestModel $target): void
    {
        throw new NotSupportedException();
        assert($source instanceof Facility);
        $target->name = isset($source->i18n['name']) ? new LocalizedString($source->i18n['name']) : null;
        $target->adminData = isset($source->admin_data) ? new NormalizedArrayDataRecord($source->admin_data) : null;
    }
}