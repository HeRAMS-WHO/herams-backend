<?php

declare(strict_types=1);

namespace prime\hydrators;

use prime\attributes\SupportedType;
use prime\helpers\LocalizedString;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\models\ActiveRecord;
use prime\models\ar\Project;
use prime\models\RequestModel;
use prime\modules\Api\models\NewProject;
use prime\modules\Api\models\UpdateProject;
use prime\objects\enums\Language;
use prime\objects\enums\ProjectStatus;
use prime\objects\enums\ProjectVisibility;
use prime\values\Latitude;
use prime\values\Longitude;
use prime\values\SurveyId;

#[
    SupportedType(NewProject::class, Project::class),
    SupportedType(UpdateProject::class, Project::class),

]
class ProjectHydrator implements ActiveRecordHydratorInterface
{
    /**
     * @param NewProject|UpdateProject $source
     * @param Project $target
     */
    public function hydrateActiveRecord(RequestModel $source, ActiveRecord $target): void
    {
        $i18n = $target->i18n;

        $i18n['title'] = $source->title->asArrayWithoutDefaultLanguage();

        $target->i18n = $i18n;
        $target->title = $source->title->getDefault();

        if ($source instanceof UpdateProject) {
//            $target->status = $source->status->value;
            $target->visibility = $source->visibility->value;
            $target->country = $source->country;
            $target->manage_implies_create_hf = $source->manageImpliesCreateHf ? 1 : 0;
            $target->latitude = $source->latitude?->value;
            $target->longitude = $source->longitude?->value;
            $target->languages = $source->languages;
        }
        $target->admin_survey_id = $source->adminSurveyId->getValue();

        $target->data_survey_id = $source->dataSurveyId->getValue();

    }



    /**
     * @param Project $source
     * @param UpdateProject $target
     * @return void
     */
    public function hydrateRequestModel(ActiveRecord $source, RequestModel $target): void
    {
        assert($target instanceof UpdateProject);
        $titleValues = [
            Language::default()->value => $source->title,
            ...$source->i18n['title'] ?? []
        ];
        $target->title = new LocalizedString($titleValues);

//        $target->status = ProjectStatus::from($source->status);
        $target->visibility = ProjectVisibility::from($source->visibility);
        $target->country = $source->country;
        $target->manageImpliesCreateHf = (bool) $source->manage_implies_create_hf;
        $target->adminSurveyId = new SurveyId($source->admin_survey_id);
        $target->dataSurveyId = new SurveyId($source->data_survey_id);
        $target->latitude = isset($source->latitude) ? new Latitude($source->latitude) : null;
        $target->longitude = isset($source->longitude) ?new Longitude($source->longitude) : null;
        $target->manageImpliesCreateHf = (bool) $source->manage_implies_create_hf;

        $target->languages = $source->languages ?? [];
    }
}
