<?php

declare(strict_types=1);

namespace herams\common\domain\workspace;

use herams\api\models\NewWorkspace;
use herams\api\models\UpdateWorkspace;
use herams\common\attributes\SupportedType;
use herams\common\enums\Language;
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
     * @param NewWorkspace $source
     * @param Workspace $target
     */
    public function hydrateActiveRecord(RequestModel $source, ActiveRecord $target): void
    {
        $i18n = $target->i18n;

        $i18n['title'] = $source->title->asArrayWithoutDefaultLanguage();

        $target->i18n = $i18n;
        $target->title = $source->title->getDefault();
        if ($source instanceof NewWorkspace) {
            $target->project_id = $source->projectId->getValue();
        }
    }

    /**
     * @param Workspace $source
     * @param UpdateWorkspace $target
     */
    public function hydrateRequestModel(ActiveRecord $source, RequestModel $target): void
    {
        $titleValues = [
            Language::default()->value => $source->title,
            ...$source->i18n['title'] ?? [],
        ];
        $target->title = new LocalizedString($titleValues);
    }
}