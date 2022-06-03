<?php
declare(strict_types=1);

namespace prime\hydrators;

use prime\attributes\SupportedType;
use prime\helpers\StringLocalization;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\models\ActiveRecord;
use prime\models\ar\Workspace;
use prime\modules\Api\models\NewWorkspace;
use yii\base\Model;

#[SupportedType(NewWorkspace::class, Workspace::class)]
class NewWorkspaceHydrator implements ActiveRecordHydratorInterface
{

    /**
     * @param NewWorkspace $source
     * @param Workspace $target
     * @return void
     */
    public function hydrateActiveRecord(Model $source, ActiveRecord $target): void
    {
        $i18n = $target->i18n;

        $i18n['title'] = $source->title->asArrayWithoutDefaultLanguage();

        $target->i18n = $i18n;
        $target->title = $source->title->getDefault();
        $target->project_id = $source->projectId->getValue();
    }
}
