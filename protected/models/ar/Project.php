<?php

namespace prime\models\ar;

use app\queries\ProjectQuery;
use Befound\ActiveRecord\Behaviors\LinkTableBehavior;
use Befound\Components\DateTime;
use prime\components\ActiveRecord;
use prime\interfaces\ReportGeneratorInterface;
use prime\models\Country;
use prime\models\permissions\Permission;
use prime\models\ar\ProjectCountry;
use prime\models\ar\Report;
use prime\models\ar\Tool;
use prime\models\ar\User;
use prime\models\ar\UserData;
use prime\models\Widget;
use prime\objects\ResponseCollection;
use Treffynnon\Navigator;
use Treffynnon\Navigator\Coordinate;
use Treffynnon\Navigator\LatLong;
use yii\helpers\ArrayHelper;
use yii\validators\DateValidator;
use yii\validators\DefaultValueValidator;
use yii\validators\RangeValidator;

/**
 * Class Project
 * @package prime\models
 *
 * @property User $user
 * @property Tool $tool
 * @property string $title
 * @property string $description
 * @property string $default_generator
 * @property
 */
class Project extends ActiveRecord
{
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'default_generator' => 'Default report'
        ]);
    }

    public function behaviors()
    {
        return [

        ];
    }

    /**
     * @return ProjectQuery
     * @throws \Exception
     */
    public static function find()
    {
        $query = parent::find();
        //if the logged in user is admin, access to all projects is allowed
        if(!app()->user->can('admin')) {
            //Select all project ids where the logged in user is owner of
            $ids = parent::find()->andWhere(['owner_id' => app()->user->id])->select('id')->column();
            //Select all project ids where the logged in user is invited to
            $ids2 = Permission::find()
                ->andWhere(
                    [
                        'source' => User::class,
                        'source_id' => app()->user->id,
                        'target' => Project::class,
                    ]
                )
                ->select('target_id');
            $query->andWhere([
                'or',
                ['id' => $ids],
                ['id' => $ids2]
            ]);
        }
        return $query;
    }

    public function getCountries()
    {
        return array_map(function($projectCountry) {
            /** @var ProjectCountry $projectCountry */
            return $projectCountry->country;
        }, $this->projectCountries);
    }

    public function getCountriesOptions()
    {
        return ArrayHelper::map(
            Country::findAll(),
            'iso_3',
            'name'
        );
    }

    public function getDefaultGenerator()
    {
        if(isset(Tool::generators()[$this->default_generator])) {
            $generatorClass = Tool::generators()[$this->default_generator];
            return new $generatorClass();
        } else {
            return null;
        }
    }

    /**
     * @return LatLong
     * TODO: calculate center taking globe curves into account
     */
    public function getLatLong()
    {
        if(isset($this->latitude, $this->longitude)) {
            $latitude = $this->latitude;
            $longitude = $this->longitude;
        } else {
            $latitude = 0;
            $longitude = 0;
            if(count($this->countries) > 0) {
                /** @var Country $country */
                foreach ($this->countries as $country) {
                    $latitude += $country->latitude;
                    $longitude += $country->longitude;
                }
                $latitude = $latitude / count($this->countries);
                $longitude = $longitude / count($this->countries);
            }
        }
        return new LatLong(
            new Coordinate($latitude),
            new Coordinate($longitude)
        );
    }

    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id'])
            ->inverseOf('projects');
    }

    public function getPermissions()
    {
        return $this->hasMany(Permission::class, ['target_id' => 'id'])
            ->andWhere(['target' => self::class]);
    }

    public function getProjectCountries()
    {
        return $this->hasMany(ProjectCountry::class, ['project_id' => 'id']);
    }

    /**
     * @return Widget
     */
    public function getProgressWidget()
    {
        $widget = $this->tool->progressWidget;
        $widget->project = $this;
        return $widget;
    }

    public function getProgressReport()
    {
        $generatorClass = $this->tool->progressOptions()[$this->tool->progress_type];
        /** @var ReportGeneratorInterface $generator */
        $generator = new $generatorClass();
        return $generator->render($this->getResponses(), app()->user->identity->createSignature());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::class, ['project_id' => 'id']);
    }

    /**
     * @return ResponseCollection
     */
    public function getResponses()
    {
        return new ResponseCollection();
    }

    public function getTool()
    {
        return $this->hasOne(Tool::class, ['id' => 'tool_id']);
    }

    /**
     * @param $reportGenerator
     * @return $this
     */
    public function getUserData($reportGenerator)
    {
        return $this->hasOne(UserData::class, ['project_id' => 'id'])
            ->andWhere(['generator' => $reportGenerator]);
    }

    public function isTransactional($operation)
    {
        return true;
    }

    public function rules()
    {
        return [
            [['title', 'description', 'owner_id', 'data_survey_eid', 'tool_id', 'closed'], 'required'],
            [['title', 'description'], 'string'],
            [['owner_id', 'data_survey_id', 'tool_id'], 'integer'],
            [['owner_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['tool_id'], 'exist', 'targetClass' => Tool::class, 'targetAttribute' => 'id'],
            [['default_generator'], RangeValidator::class, 'range' => function(self $model, $attribute) {return array_keys($model->generatorOptions);}],
            [['closed'], DateValidator::class,'format' => 'php:' . DateTime::MYSQL_DATETIME],

            // Save NULL instead of "" when no default report is selected.
            [['default_generator'], DefaultValueValidator::class]
        ];
    }

    public function scenarios()
    {
        return [
            'close' => ['closed']
        ];
    }

    public function userCan($operation, User $user = null)
    {
        $user = (isset($user)) ? (($user instanceof User) ? $user : User::findOne($user)) : app()->user->identity;

        $result = parent::userCan($operation, $user);
        if(!$result) {
            $result = $result || $this->owner_id == $user->id;
            $result = $result || Permission::isAllowed($user, $this, $operation);
        }
        return $result;
    }

    public function getGeneratorOptions()
    {
        return $this->tool->generatorOptions;
    }
}