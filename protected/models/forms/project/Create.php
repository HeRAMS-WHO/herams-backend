<?php
declare(strict_types=1);

namespace prime\models\forms\project;

use prime\models\ar\Project;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use function iter\filter;

class Create extends Model
{
    public string $title = '';
    public string $visibility = Project::VISIBILITY_PUBLIC;
    public ?int $base_survey_eid = null;

    public function visibilityOptions(): array
    {
        return [
            Project::VISIBILITY_HIDDEN => Yii::t('models.project.create', 'Hidden, this project is only visible to people with permissions'),
            Project::VISIBILITY_PUBLIC => Yii::t('models.project.create', 'Public, anyone can view this project'),
            Project::VISIBILITY_PRIVATE => Yii::t('models.project.create', 'Private, this project is visible on the map and in the list, but people need permission to view it')
        ];
    }

    public function dataSurveyOptions(): array
    {
        $existing = Project::find()->select('base_survey_eid')->indexBy('base_survey_eid')->column();

        $surveys = filter(function ($details) use ($existing) {
            return $this->base_survey_eid == $details['sid'] || !isset($existing[$details['sid']]);
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
            [['visibility'], RangeValidator::class, 'range' => array_keys($this->visibilityOptions())],
//            [['hidden'], BooleanValidator::class],
//            [['latitude', 'longitude'], NumberValidator::class, 'integerOnly' => false],
//            [['typemapAsJson', 'overridesAsJson'], SafeValidator::class],
//            [['status'], RangeValidator::class, 'range' => array_keys($this->statusOptions())],
//
//            [['country'], function () {
//                $data = new ISO3166();
//                try {
//                    $data->alpha3($this->country);
//                } catch (\Throwable $t) {
//                    $this->addError('country', $t->getMessage());
//                }
//            }],
//            [['country'], DefaultValueValidator::class, 'value' => null],
//            [['manage_implies_create_hf'], BooleanValidator::class]
        ];
    }
}
