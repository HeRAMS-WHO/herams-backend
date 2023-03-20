<?php

declare(strict_types=1);

namespace herams\api\domain\workspace;

use herams\common\attributes\SupportedType;
use herams\common\helpers\LocalizedString;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\models\ActiveRecord;
use herams\common\models\RequestModel;
use herams\common\models\Workspace;

#[
    SupportedType(NewWorkspace::class, Workspace::class),
    SupportedType(UpdateWorkspace::class, Workspace::class)
]
class WorkspaceHydrator implements ActiveRecordHydratorInterface
{
    /**
     * @param NewWorkspace|UpdateWorkspace $source
     * @param Workspace $target
     */
    public function hydrateActiveRecord(RequestModel $source, ActiveRecord $target): void
    {
        $i18n = $target->i18n;
        if (isset($source->title)) {
            $i18n['title'] = $source->title->asDictionary();
        }

        $target->i18n = $i18n;
        if (isset($source->projectId)) {
            $target->project_id = $source->projectId->getValue();
        }
    }

    /**
     * @param Workspace $source
     * @param UpdateWorkspace $target
     */
    public function hydrateRequestModel(ActiveRecord $source, RequestModel $target): void
    {
        $target->title = new LocalizedString($source->i18n['title']);
    }
}
