<?php

declare(strict_types=1);

namespace herams\api\domain\project;

use herams\common\attributes\SupportedType;
use herams\common\enums\ProjectVisibility;
use herams\common\helpers\LocalizedString;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\models\ActiveRecord;
use herams\common\models\Project;
use herams\common\models\RequestModel;
use herams\common\values\DatetimeValue;
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
        $i18n['title'] = $source->title->asDictionary();
        $target->i18n = $i18n;
        $target->visibility = $source->visibility->value;
        $target->country = $source->country;
        $target->latitude = $source->latitude?->value;
        $target->longitude = $source->longitude?->value;
        $target->languages = $source->languages;
        $target->primary_language = $source->primaryLanguage;
        $target->admin_survey_id = $source->adminSurveyId->getValue();
        $target->data_survey_id = $source->dataSurveyId->getValue();
        $target->dashboard_url = $source->dashboardUrl === '' ? null : $source->dashboardUrl;
        $target->created_by = $source->createdBy;
        $target->last_modified_by = $source->lastModifiedBy;
        $target->last_modified_date = $source->lastModifiedDate->getValue();
        $target->created_date = $source->createdDate->getValue();
        \Yii::error($target->attributes);
    }

    /**
     * @param Project $source
     * @param UpdateProject|NewProject $target
     */
    public function hydrateRequestModel(ActiveRecord $source, RequestModel $target): void
    {
        assert($target instanceof UpdateProject);

        $target->title = new LocalizedString($source->i18n['title'] ?? []);

        $target->visibility = ProjectVisibility::from(strtolower($source->visibility));
        $target->country = $source->country;
        $target->primaryLanguage = $source->primary_language;
        $target->adminSurveyId = new SurveyId($source->admin_survey_id);
        $target->dataSurveyId = new SurveyId($source->data_survey_id);
        $target->latitude = isset($source->latitude) ? new Latitude($source->latitude) : null;
        $target->longitude = isset($source->longitude) ? new Longitude($source->longitude) : null;
        $target->dashboardUrl = $source->dashboard_url ?? '';
        $target->lastModifiedDate = new DatetimeValue($source->last_modified_date);
        $target->languages = $source->languages ?? [];
        $target->createdBy = $source->created_by;
        $target->createdDate = new DatetimeValue($source->created_date);
        $target->lastModifiedBy = $source->last_modified_by;
    }
}
