<?php

declare(strict_types=1);

namespace prime\models\forms\project;

use prime\models\ar\Project;
use prime\models\ar\Survey;
use prime\objects\enums\ProjectVisibility;
use prime\validators\RangeValidator;
use prime\values\SurveyId;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\InlineValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

use function iter\filter;
use function iter\mapWithKeys;
use function iter\toArrayWithKeys;

class Create extends Model
{
    public null|SurveyId $admin_survey_id = null;

    public null|int $base_survey_eid = null;

    public null|SurveyId $data_survey_id = null;

    public string $title = '';

    public ProjectVisibility $visibility;

    public function __construct()
    {
        parent::__construct([]);
        $this->visibility = ProjectVisibility::Public;
    }

    public function attributeLabels(): array
    {
        return Project::labels();
    }

    public function dataSurveyOptions(): array
    {
        $existing = Project::find()->select('base_survey_eid')->indexBy('base_survey_eid')->column();

        $surveys = filter(function ($details) use ($existing) {
            return (isset($this->base_survey_eid) && $this->base_survey_eid == $details['sid']) || ! isset($existing[$details['sid']]);
        }, app()->limesurveyDataProvider->listSurveys());

        $result = ArrayHelper::map($surveys, 'sid', function ($details) {
            return $details['surveyls_title'] . (($details['active'] == 'N') ? " (INACTIVE)" : "");
        });

        return $result;
    }

    public function formName(): string
    {
        return 'Project';
    }

    public function rules(): array
    {
        return [
            [['title'], RequiredValidator::class],
            [['title'],
                StringValidator::class,
                'min' => 1,
            ],
            [['title'],
                UniqueValidator::class,
                'targetAttribute' => 'title',
                'targetClass' => Project::class,
            ],
            [['base_survey_eid'],
                RangeValidator::class,
                'range' => array_keys($this->dataSurveyOptions()),
            ],
            [['admin_survey_id', 'data_survey_id'],
                RangeValidator::class,
                'range' => array_keys($this->surveyIdOptions()),
            ],
            [['visibility'], SafeValidator::class],
            [['data_survey_id', 'admin_survey_id', 'base_survey_eid'],
                function (string $attribute, null|array $params, InlineValidator $validator) {
                    if (empty($this->base_survey_eid) && (empty($this->admin_survey_id) || empty($this->data_survey_id))) {
                        $this->addError(
                            $attribute,
                            \Yii::t(
                                'app',
                                'Either {baseSurveyEid} or {adminSurveyId} and {dataSurveyId} must be set.',
                                [
                                    'baseSurveyEid' => $this->getAttributeLabel('base_survey_eid'),
                                    'adminSurveyId' => $this->getAttributeLabel('admin_survey_id'),
                                    'dataSurveyId' => $this->getAttributeLabel('data_survey_id'),
                                ]
                            )
                        );
                    }
                },
                'skipOnEmpty' => false,
            ],
        ];
    }

    public function surveyIdOptions(): array
    {
        return toArrayWithKeys(mapWithKeys(function (Survey $survey) {
            return $survey->getTitle();
        }, Survey::find()->indexBy('id')->each()));
    }
}
