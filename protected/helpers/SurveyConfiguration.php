<?php

namespace prime\helpers;

use herams\common\enums\ProjectVisibility;
use herams\common\helpers\ConfigurationProvider;
use herams\common\models\Project;
use yii\helpers\VarDumper;

class SurveyConfiguration
{
    public static function retrieveUserLanguages(): array
    {
        $configurationProvider = new ConfigurationProvider();
        $choices = [];
        foreach ($configurationProvider->getPlatformLocales() as $choice) {
            $choices[] = [
                'value' => $choice->locale,
                'text' => $choice->label,
            ];
        }
        return $choices;
    }

    public static function forCreatingProject(): array
    {
        return [
            "title" => "Create project",
            "pages" => [
                [
                    "name" => "page1",
                    "elements" => [
                        [
                            "type" => "localizableprojecttext",
                            "name" => "title",
                            "title" => "Project name",
                            "isRequired" => true,
                        ],
                        [
                            "type" => "currentlanguage",
                            "name" => "lang",
                            "visible" => false,
                            "isRequired" => true,
                            "readOnly" => true,
                        ],
                        [
                            "type" => "checkbox",
                            "name" => "languages",
                            "title" => "Project languages",
                            "description" => "These will be available for workspace & facility data",
                            "defaultValue" => [
                                \Yii::$app->language,
                            ],
                            "isRequired" => true,
                            "choices" => self::retrieveUserLanguages(),
                        ],
                        [
                            "type" => "radiogroup",
                            "name" => "primaryLanguage",
                            "title" => "Primary language for this project",
                            "description" => "Primary language for the project",
                            "isRequired" => true,
                            "choicesFromQuestion" => "languages",
                            "choicesFromQuestionMode" => "selected",
                            "defaultValue" => \Yii::$app->language,
                        ],
                        [
                            "type" => "projectvisibility",
                            "name" => "projectvisibility",
                            "title" => "Visibility",
                            "description" => "Visibility",
                            "isRequired" => true,
                        ],
                        [
                            "type" => "surveypicker",
                            "name" => "adminSurveyId",
                            "title" => "Admin survey",
                            "description" => "Survey to use for facility settings",
                            "isRequired" => true,
                        ],
                        [
                            "type" => "surveypicker",
                            "name" => "dataSurveyId",
                            "title" => "Data survey",
                            "description" => "Survey to use for facility data",
                            "isRequired" => true,
                            "validators" => [
                                [
                                    "type" => "expression",
                                    "text" => "Data and admin survey should not be the same",
                                    "expression" => "{adminSurveyId} <> {dataSurveyId}",
                                ],
                            ],
                        ],
                        [
                            "name" => "country",
                            "type" => "countrypicker",
                            "title" => "Country",
                            "isRequired" => true,
                            "defaultValue" => "XXX",
                        ],
                        [
                            "type" => "latitude",
                            "name" => "latitude",
                            "title" => "Latitude",
                            "isRequired" => true,
                        ],
                        [
                            "type" => "longitude",
                            "name" => "longitude",
                            "title" => "Longitude",
                            "isRequired" => true,
                        ],
                        [
                            "type" => "text",
                            "name" => "dashboardUrl",
                            "title" => "External dashboard URL",
                            "description" => "Leave empty to use built-in dashboarding",
                            "validators" => [
                                [
                                    "type" => "regex",
                                    "text" => "URL must start with https://",
                                    "regex" => "^https://",
                                ],
                            ],
                            "inputType" => "url",
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function forUpdatingProject(Project $project): array
    {
        //VarDumper::dump($project, 10, true);
        return [
            "title" => "Create project",
            "pages" => [
                [
                    "name" => "page1",
                    "elements" => [
                        [
                            "type" => "localizableprojecttext",
                            "name" => "title",
                            "title" => "Project name",
                            "isRequired" => true,
                            "defaultValue" => $project->i18n['title'],
                        ],
                        [
                            "type" => "currentlanguage",
                            "name" => "lang",
                            "visible" => false,
                            "isRequired" => true,
                            "readOnly" => true,
                            "defaultValue" => $project->primary_language,
                        ],
                        [
                            "type" => "checkbox",
                            "name" => "languages",
                            "title" => "Project languages",
                            "description" => "These will be available for workspace & facility data",
                            "defaultValue" => $project->languages,
                            "isRequired" => true,
                            "choices" => self::retrieveUserLanguages(),
                        ],
                        [
                            "type" => "radiogroup",
                            "name" => "primaryLanguage",
                            "title" => "Primary language for this project",
                            "description" => "Primary language for the project",
                            "isRequired" => true,
                            "choicesFromQuestion" => "languages",
                            "choicesFromQuestionMode" => "selected",
                            "defaultValue" => $project->primary_language,
                        ],
                        [
                            "type" => "projectvisibility",
                            "name" => "projectvisibility",
                            "title" => "Visibility",
                            "description" => "Visibility",
                            "isRequired" => true,
                            "defaultValue" => ProjectVisibility::from(strtolower($project->visibility))->label()
                        ],
                        [
                            "type" => "surveypicker",
                            "name" => "adminSurveyId",
                            "title" => "Admin survey",
                            "description" => "Survey to use for facility settings",
                            "isRequired" => true,
                            "defaultValue" => $project->admin_survey_id,
                        ],
                        [
                            "type" => "surveypicker",
                            "name" => "dataSurveyId",
                            "title" => "Data survey",
                            "description" => "Survey to use for facility data",
                            "isRequired" => true,
                            "validators" => [
                                [
                                    "type" => "expression",
                                    "text" => "Data and admin survey should not be the same",
                                    "expression" => "{adminSurveyId} <> {dataSurveyId}",
                                ],
                            ],
                            "defaultValue" => $project->data_survey_id,
                        ],
                        [
                            "name" => "country",
                            "type" => "countrypicker",
                            "title" => "Country",
                            "isRequired" => true,
                            "defaultValue" => $project->country,
                        ],
                        [
                            "type" => "latitude",
                            "name" => "latitude",
                            "title" => "Latitude",
                            "isRequired" => true,
                            "defaultValue" => $project->latitude,
                        ],
                        [
                            "type" => "longitude",
                            "name" => "longitude",
                            "title" => "Longitude",
                            "isRequired" => true,
                            "defaultValue" => $project->longitude,
                        ],
                        [
                            "type" => "text",
                            "name" => "dashboardUrl",
                            "title" => "External dashboard URL",
                            "description" => "Leave empty to use built-in dashboarding",
                            "validators" => [
                                [
                                    "type" => "regex",
                                    "text" => "URL must start with https://",
                                    "regex" => "^https://",
                                ],
                            ],
                            "inputType" => "url",
                            "defaultValue" => $project->dashboard_url,
                        ],
                    ],
                ],
            ],
        ];
    }
}
