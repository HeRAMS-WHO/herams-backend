<?php
declare(strict_types=1);

namespace prime\models\forms\project;

use prime\models\ar\Project;
use prime\objects\enums\ProjectVisibility;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use function iter\filter;

class Create extends Model
{
    public string $title = '';
    public ProjectVisibility $visibility;
    public null|int $base_survey_eid = null;

    public function __construct()
    {
        parent::__construct([]);
        $this->visibility = ProjectVisibility::public();
    }


    public function dataSurveyOptions(): array
    {
        $existing = Project::find()->select('base_survey_eid')->indexBy('base_survey_eid')->column();

        $surveys = filter(function ($details) use ($existing) {
            return (isset($this->base_survey_eid) && $this->base_survey_eid == $details['sid']) || !isset($existing[$details['sid']]);
        }, app()->limesurveyDataProvider->listSurveys());

        $result = ArrayHelper::map($surveys, 'sid', function ($details) {
            return $details['surveyls_title'] . (($details['active'] == 'N') ? " (INACTIVE)" : "");
        });

        return $result;
    }

    public function rules(): array
    {
        return [
            [['title', 'base_survey_eid'], RequiredValidator::class],
            [['title'], StringValidator::class, 'min' => 1],
            [['title'], UniqueValidator::class, 'targetAttribute' => 'title', 'targetClass' => Project::class],
            [['base_survey_eid'], RangeValidator::class, 'range' => array_keys($this->dataSurveyOptions())],
            [['visibility'], SafeValidator::class],
        ];
    }
}
