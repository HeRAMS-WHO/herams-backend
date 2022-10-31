<?php

declare(strict_types=1);

namespace prime\hydrators;

use herams\api\models\NewWorkspace;
use herams\api\models\UpdateWorkspace;
use prime\attributes\SupportedType;
use prime\helpers\LocalizedString;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\models\ActiveRecord;
use prime\models\ar\Workspace;
use prime\models\RequestModel;
use prime\objects\enums\Language;

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
