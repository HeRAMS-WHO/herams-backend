<?php

declare(strict_types=1);

namespace prime\helpers;

use herams\common\enums\ConfigurationKey;
use herams\common\models\Configuration;
use herams\common\values\SurveyId;

class ConfigurationProvider
{
    public function getUpdateWorkspaceSurveyId(): SurveyId
    {
        $data = Configuration::find()->andWhere([
            'key' => ConfigurationKey::UpdateWorkspaceSurveyId->value,
        ])->one();
        return new SurveyId($data->value);
    }

    public function getCreateProjectSurveyId(): SurveyId
    {
        $data = Configuration::find()->andWhere([
            'key' => ConfigurationKey::CreateProjectSurveyId->value,
        ])->one();
        return new SurveyId($data->value);
    }

    public function getCreateWorkspaceSurveyId(): SurveyId
    {
        $data = Configuration::find()->andWhere([
            'key' => ConfigurationKey::CreateWorkspaceSurveyId->value,
        ])->one();
        return new SurveyId($data->value);
    }

    public function getUpdateProjectSurveyId(): SurveyId
    {
        $data = Configuration::find()->andWhere([
            'key' => ConfigurationKey::UpdateProjectSurveyId->value,
        ])->one();
        return new SurveyId($data->value);
    }
}