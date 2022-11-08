<?php

declare(strict_types=1);

namespace herams\common\domain\project;

use herams\api\models\NewProject;
use herams\api\models\UpdateProject;
use herams\common\attributes\SupportedType;
use herams\common\enums\Language;
use herams\common\enums\ProjectVisibility;
use herams\common\helpers\LocalizedString;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\models\ActiveRecord;
use herams\common\models\Project;
use herams\common\models\RequestModel;
use herams\common\values\Latitude;
use herams\common\values\Longitude;
use herams\common\values\SurveyId;

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
     */
    public function hydrateRequestModel(ActiveRecord $source, RequestModel $target): void
    {
        assert($target instanceof UpdateProject);
        $titleValues = [
            Language::default()->value => $source->title,
            ...$source->i18n['title'] ?? [],
        ];
        $target->title = new LocalizedString($titleValues);

//        $target->status = ProjectStatus::from($source->status);
        $target->visibility = ProjectVisibility::from($source->visibility);
        $target->country = $source->country;
        $target->manageImpliesCreateHf = (bool) $source->manage_implies_create_hf;
        $target->adminSurveyId = new SurveyId($source->admin_survey_id);
        $target->dataSurveyId = new SurveyId($source->data_survey_id);
        $target->latitude = isset($source->latitude) ? new Latitude($source->latitude) : null;
        $target->longitude = isset($source->longitude) ? new Longitude($source->longitude) : null;
        $target->manageImpliesCreateHf = (bool) $source->manage_implies_create_hf;

        $target->languages = $source->languages ?? [];
    }
}
