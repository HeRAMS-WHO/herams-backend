<?php

namespace prime\models\ar;

use app\queries\ProjectQuery;
use Befound\ActiveRecord\Behaviors\LinkTableBehavior;
use Befound\Components\DateTime;
use Carbon\Carbon;
use prime\components\ActiveQuery;
use prime\components\ActiveRecord;
use prime\factories\GeneratorFactory;
use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\models\Country;
use prime\models\forms\projects\Token;
use prime\models\permissions\Permission;
use prime\models\ar\ProjectCountry;
use prime\models\ar\Report;
use prime\models\ar\Tool;
use prime\models\ar\User;
use prime\models\ar\UserData;
use prime\models\Widget;
use prime\objects\ResponseCollection;
use prime\objects\SurveyCollection;
use Prophecy\Argument\Token\TokenInterface;
use SamIT\LimeSurvey\Interfaces\WritableTokenInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use Treffynnon\Navigator;
use Treffynnon\Navigator\Coordinate;
use Treffynnon\Navigator\LatLong;
use yii\base\Security;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\validators\DateValidator;
use yii\validators\DefaultValueValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\web\JsExpression;

/**
 * Class Project
 * @package prime\models
 *
 * @property User $user
 * @property Tool $tool
 * @property string $title
 * @property string $description
 * @property string $default_generator
 * @property string $country_iso_3
 * @property int $data_survey_eid The associated data survey.
 * @property Country $country
 *
 * @method static ProjectQuery find()
 */
class Project extends ActiveRecord implements ProjectInterface
{
    /**
     * @var WritableTokenInterface
     */
    protected $_token;
    /**
     * @var Client
     */
    protected $limeSurvey;

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'default_generator' => \Yii::t('app', 'Default report'),
            'country_iso_3' => \Yii::t('app', 'Country'),
            'tool_id' => \Yii::t('app', 'Tool'),
            'locality_name' => \Yii::t('app', 'Locality')
        ]);
    }

    public function dataSurveyOptions()
    {
        return $this->tool->dataSurveyOptions();
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return Country::findOne($this->country_iso_3);
    }


    public function countryOptions()
    {
        $options = ArrayHelper::map(
            Country::findAll(),
            'iso_3',
            'name'
        );
        asort($options);
        return $options;
    }

    public function generatorOptions()
    {
        return isset($this->tool) ? array_intersect_key(GeneratorFactory::options(), array_flip($this->tool->generators->asArray())) : [];
    }

    public function getDefaultGenerator()
    {
        return GeneratorFactory::get($this->default_generator);
    }

    /**
     * @return LatLong
     */
    public function getLatLong()
    {
        if(isset($this->latitude, $this->longitude)) {
            $latitude = $this->latitude;
            $longitude = $this->longitude;
        } else {
            $latitude = $this->country->latitude;
            $longitude = $this->country->longitude;
        }
        return new LatLong(
            new Coordinate(rad2deg($latitude)),
            new Coordinate(rad2deg($longitude))
        );
    }

    /**
     * Return the name of the locality. If locality_name isn't set, return name of the country
     * @return string
     */
    public function getLocality()
    {
        if(!empty($this->locality_name)) {
            $result = "{$this->country->name} ({$this->locality_name})";
        } else {
            $result = $this->country->name;
        }
        return $result;
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
        /** @var ReportGeneratorInterface $generator */
        $generator = GeneratorFactory::get($this->tool->progress_type);
        return $generator->render($this->getResponses(), $this->getSurvey(), $this, app()->user->identity->createSignature());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::class, ['project_id' => 'id']);
    }

    /**
     * @return ResponseCollectionInterface
     */
    public function getResponses()
    {
        $result = new ResponseCollection();
        foreach ($this->limeSurvey->getResponsesByToken($this->data_survey_eid, $this->token) as $response) {
            $result->append($response);
        }
        /**
         * @todo Refactor this to be somewhere else.
         * Special handling for CCPM.
         */
        if ($this->tool->acronym == 'CCPM') {
            foreach ($this->limeSurvey->getResponsesByToken(67825, $this->token) as $response) {
                $result->append($response);
            }
        }
        return $result;
    }

    /**
     * @return SurveyCollectionInterface
     * TODO: Implement correct function
     */
    public function getSurvey()
    {
        $surveys = new SurveyCollection();
        /** @var ResponseInterface $response */
        foreach($this->responses as $response) {
            if(!$surveys->offsetExists($response->getSurveyId())) {
                $survey = $this->limeSurvey->getSurvey($response->getSurveyId());
                $surveys->add($survey->getId(), $survey);
            }
        }
        return $surveys;
    }

    public function getTool()
    {
        return $this->hasOne(Tool::class, ['id' => 'tool_id']);
    }

    /**
     * @return string
     */
    public function getToolImagePath()
    {
        return app()->urlManager->createAbsoluteUrl($this->tool->imageUrl);
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

    public function ownerOptions()
    {
        return \yii\helpers\ArrayHelper::map(\prime\models\ar\User::find()->all(), 'id', 'name');
    }

    public function rules()
    {
        return [
            [['title', 'description', 'owner_id', 'data_survey_eid', 'tool_id', 'closed', 'country_iso_3'], RequiredValidator::class],
            [['title', 'description', 'locality_name'], StringValidator::class],
            [['owner_id', 'data_survey_id', 'tool_id'], 'integer'],
            [['owner_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['tool_id'], 'exist', 'targetClass' => Tool::class, 'targetAttribute' => 'id'],
            [
                ['default_generator'],
                RangeValidator::class,
                'range' => function(self $model, $attribute) { return array_keys($model->generatorOptions()); },
                'enableClientValidation'=> false
            ],
            [['closed'], DateValidator::class,'format' => 'php:' . DateTime::MYSQL_DATETIME],
            [['latitude', 'longitude'], NumberValidator::class],
            // Save NULL instead of "" when no default report is selected.
            [['default_generator', 'locality_name', 'latitude', 'longitude'], DefaultValueValidator::class],
            ['country_iso_3', RangeValidator::class, 'range' => ArrayHelper::getColumn(Country::findAll(), 'iso_3')],
        ];
    }

    public function scenarios()
    {
        return [
            'close' => ['closed']
        ];
    }

    public function toolOptions()
    {
        return \yii\helpers\ArrayHelper::map(\prime\models\ar\Tool::find()->all(), 'id', 'title');
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

    /**
     * @return WritableTokenInterface
     */
    public function getToken()
    {
        if (!isset($this->_token)) {
            // Always attempt creation.
            $this->limeSurvey->createToken($this->data_survey_eid, ['token' => $this->token]);
            $token = $this->limeSurvey->getToken($this->data_survey_eid, $this->token);

            $token->setFirstName($this->getLocality());
            $token->setLastName($this->owner->lastName);
            $token->setValidFrom(new Carbon($this->created));

            $this->_token = $token;
        }
        return $this->_token;
    }

    public static function listDependencies()
    {
        return [
            'setLimeSurvey' => Client::class
        ];
    }

    /**
     * @param Client $limeSurvey
     */
    public function setLimeSurvey(Client $limeSurvey) {
        $this->limeSurvey = $limeSurvey;
    }


}