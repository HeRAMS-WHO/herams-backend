<?php
declare(strict_types=1);

namespace prime\models\forms\project;

use prime\behaviors\LocalizableWriteBehavior;
use prime\models\ar\Project;
use prime\values\ProjectId;
use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\validators\BooleanValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use function iter\filter;

class Update extends Model
{
    public int $id;
    public string $title = '';
    public string $visibility = Project::VISIBILITY_PUBLIC;

    public ?int $base_survey_eid = null;

    public array $i18n = [];

    public ?float $latitude = null;
    public ?float $longitude = null;

    public int $status = Project::STATUS_ONGOING;

    public ?string $country = null;
    public bool $hidden = false;
    public bool $manage_implies_create_hf = false;

    public function __construct(ProjectId $id)
    {
        parent::__construct([]);
        $this->id = $id->getValue();
    }


    public function behaviors()
    {
        return [
            LocalizableWriteBehavior::class => [
                'class' => LocalizableWriteBehavior::class,
                'attributes' => ['title']
            ]
        ];
    }


    public function visibilityOptions(): array
    {
        return [
            Project::VISIBILITY_HIDDEN => Yii::t('app.models.project', 'Hidden, this project is only visible to people with permissions'),
            Project::VISIBILITY_PUBLIC => Yii::t('app.models.project', 'Public, anyone can view this project'),
            Project::VISIBILITY_PRIVATE => Yii::t('app.models.project', 'Private, this project is visible on the map and in the list, but people need permission to view it')
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
            [['title'], UniqueValidator::class, 'targetAttribute' => 'title', 'targetClass' => Project::class, 'filter' => ['not', ['id' => $this->id]]],
            [['base_survey_eid'], RangeValidator::class, 'range' => function () {
                return array_keys($this->dataSurveyOptions());
            }],
            [['visibility'], RangeValidator::class, 'range' => array_keys($this->visibilityOptions())],
            [['hidden'], BooleanValidator::class],
            [['latitude', 'longitude'], NumberValidator::class, 'integerOnly' => false],
            [['typemapAsJson', 'overridesAsJson'], SafeValidator::class],
            [['status'], RangeValidator::class, 'range' => array_keys(Project::statusOptions())],

            [['country'], function () {
                $data = new ISO3166();
                try {
                    $data->alpha3($this->country);
                } catch (\Throwable $t) {
                    $this->addError('country', $t->getMessage());
                }
            }],
            [['manage_implies_create_hf'], BooleanValidator::class]
        ];
    }
}
