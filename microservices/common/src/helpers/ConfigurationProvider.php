<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\enums\ConfigurationKey;
use herams\common\models\Configuration;
use herams\common\values\SurveyId;

class ConfigurationProvider
{
    /**
     * @return Locale
     */
    public function getPlatformLocales(): array
    {
        $data = Configuration::find()->andWhere([
            'key' => ConfigurationKey::Locales->value,
        ])->one();

        return Locale::fromValues($data?->value ?? ["en", "fr", "ar", "es", "ru", "zh"]);
    }

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
