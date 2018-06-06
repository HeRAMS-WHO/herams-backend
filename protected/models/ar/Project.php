<?php

namespace prime\models\ar;

use app\queries\ProjectQuery;
use Befound\ActiveRecord\Behaviors\LinkTableBehavior;
use Befound\Components\DateTime;
use Carbon\Carbon;
use prime\interfaces\AuthorizableInterface;
use prime\models\ActiveRecord;
use prime\factories\GeneratorFactory;
use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\models\Country;
use prime\models\permissions\Permission;
use prime\models\Widget;
use prime\objects\ResponseCollection;
use prime\objects\SurveyCollection;
use prime\traits\LoadOneAuthTrait;
use SamIT\LimeSurvey\Interfaces\WritableTokenInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use Treffynnon\Navigator;
use Treffynnon\Navigator\Coordinate;
use Treffynnon\Navigator\LatLong;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\validators\DateValidator;
use yii\validators\DefaultValueValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use yii\web\UrlManager;

/**
 * Class Project
 * @package prime\models
 *
 * @property User $owner
 * @property Tool $tool
 * @property string $title
 * @property string $description
 * @property string $default_generator
 * @property string $country_iso_3
 * @property int $data_survey_eid The associated data survey.
 * @property int $tool_id
 * @property string $locality_name
 * @property datetime $created
 * @property boolean $isClosed
 * @property Country $country
 * @property int $owner_id
 *
 * @method static ProjectQuery find()
 */
class Project extends ActiveRecord implements ProjectInterface, AuthorizableInterface
{
    use LoadOneAuthTrait;
    /**
     * @var WritableTokenInterface
     */
    protected $_token;
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'default_generator' => \Yii::t('app', 'Default report'),
            'country_iso_3' => \Yii::t('app', 'Country'),
            'tool_id' => \Yii::t('app', 'Tool'),
            'locality_name' => \Yii::t('app', 'Locality'),
            'data_survey_eid' => \Yii::t('app', 'Data survey'),
            'owner_id' => \Yii::t('app', 'Owner')
        ]);
    }

    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'token' => 'Note that the first name and last name fields in the tokens will be overridden upon project creation!.'
        ]);
    }


    public function dataSurveyOptions()
    {
        try {
            // Get base survey.
            $prefix = $this->tool->getBaseSurvey()->getTitle();
            $result =  array_filter($this->tool->dataSurveyOptions(), function ($option) use ($prefix) {
                return substr_compare($prefix, $option, 0, strlen($prefix)) === 0;
            });
            return $result;
        } catch (\Exception $e) {

        }
        return [];
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

    /**
     * @return ReportGeneratorInterface
     */
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
            ->inverseOf('ownedProjects');
    }

    public function getPermissions(): ActiveQuery
    {
        return $this->hasMany(Permission::class, ['target_id' => 'id'])
            ->andWhere(['target' => self::class]);
    }

    public function getProgressReport()
    {
        if (isset($this->tool->progress_type)) {
            /** @var ReportGeneratorInterface $generator */
            $generator = GeneratorFactory::get($this->tool->progress_type);

            return $generator->render($this->getResponses(), $this->getSurvey(), $this,
                app()->user->identity->createSignature(), new UserData());
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::class, ['project_id' => 'id']);
    }

    // Cache for getResponses();
    private $_responses;
    
    /**
     * @return ResponseCollectionInterface
     */
    public function getResponses()
    {
        if (!isset($this->_responses)) {
            $this->_responses = new ResponseCollection();
            foreach ($this->limeSurvey->getResponsesByToken($this->data_survey_eid, $this->token) as $response) {
                $this->_responses->append($response);
            }
            /**
             * @todo Refactor this to be somewhere else.
             * Special handling for CCPM.
             */
            if ($this->tool->acronym == 'CCPM') {
                foreach ($this->limeSurvey->getResponsesByToken(67825, $this->token) as $response) {
                    $this->_responses->append($response);
                }
            }
        }
        return $this->_responses;
    }

    /**
     * @return SurveyCollectionInterface
     * TODO: Implement correct function, language of surveys should be correct!
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
        return Url::to($this->tool->imageUrl, true);
    }

    public function getImage()
    {
        return $this->tool->getImage();
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
            [['title', 'description', 'owner_id', 'data_survey_eid', 'tool_id', 'country_iso_3'], RequiredValidator::class],
            [['title', 'description', 'locality_name'], StringValidator::class],
            [['owner_id', 'data_survey_eid', 'tool_id'], 'integer'],
            [['owner_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['tool_id'], 'exist', 'targetClass' => Tool::class, 'targetAttribute' => 'id'],
            [
                ['default_generator'],
                RangeValidator::class,
                'range' => function(self $model, $attribute) { return array_keys($model->generatorOptions()); },
                'enableClientValidation'=> false
            ],
            [['closed'], DateValidator::class,'format' => 'php:' . DateTime::MYSQL_DATETIME, 'skipOnEmpty' => true],
            [['latitude', 'longitude'], NumberValidator::class],
            // Save NULL instead of "" when no default report is selected.
            [['default_generator', 'locality_name', 'latitude', 'longitude'], DefaultValueValidator::class],
            ['country_iso_3', RangeValidator::class, 'range' => ArrayHelper::getColumn(Country::findAll(), 'iso_3')],
            ['token', UniqueValidator::class]
        ];
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            'close' => ['closed'],
            'reOpen' => ['closed']
        ]);
    }

    public function transactions()
    {
        return array_merge(parent::transactions(), [
            'create' => [self::OP_INSERT]
        ]);
    }


    /**
     * @param $operation
     * @param User|null $user
     * @return bool
     */
    public function userCan($operation, User $user)
    {
        $result = parent::userCan($operation, $user) || ($operation === Permission::PERMISSION_READ && app()->user->can('manager'));
        if(!$result) {
            $result = $result
                // User owns the project.
                || $this->owner_id == $user->id
                // This is the health cluster mapping project, everyone can read it.
                || ($this->id == Setting::get('healthClusterDashboardProject') && $operation == Permission::PERMISSION_READ)
                || Permission::isAllowed($user, $this, $operation);
        }
        return $result;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // Grant read / write permissions on the project to the creator.
        if ($insert
            && !app()->user->can('admin')
            && isset(app()->user->identity)
        )
        {
            if (!Permission::grant(app()->user->identity, $this, Permission::PERMISSION_ADMIN)) {
                throw new \Exception("Failed to grant permission.");
            }
        }
    }


    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);
        if ($result && empty($this->getAttribute('token')) && isset($this->data_survey_eid)) {
                // Attempt creation of a token.
                $token = $this->getLimeSurvey()->createToken($this->data_survey_eid, [
                    'token' => app()->security->generateRandomString(15)
                ]);
                $token->setFirstName($this->getLocality());

                $token->setValidFrom(new Carbon($this->created));
                $this->_token = $token;
                $this->setAttribute('token', $token->getToken());
                return $token->save();
        }


        return $result;
    }




    /**
     * @return WritableTokenInterface
     */
    public function getToken()
    {
        if (!isset($this->_token)) {

            /** @var WritableTokenInterface $token */
            $token = $this->getLimeSurvey()->getToken($this->data_survey_eid, $this->token);

            $token->setFirstName($this->getLocality());
            if (isset($this->owner)) {
                $token->setLastName($this->owner->lastName);
            }
            $token->setValidFrom(new Carbon($this->created));
            $token->save();
            $this->_token = $token;
        }
        return $this->_token;
    }


    /**
     * @return Client $limeSurvey
     */
    public function getLimeSurvey()
    {
        return \Yii::$app->get('limeSurvey');
    }

    public function getSurveyUrl()
    {
        /**
         * @todo Refactor this to be somewhere else.
         * Special handling for CCPM.
         */
        if ($this->tool->acronym == 'CCPM') {
            $surveyId = 67825;
        } else {
            $surveyId = $this->data_survey_eid;
        }

        return $this->getLimeSurvey()->getUrl(
            $surveyId,
            [
                'token' => $this->getAttribute('token'),
                'newtest' => 'Y'
            ]
        );
    }

    public function getIsClosed()
    {
        return isset($this->closed);
    }

    /**
     * @return string The name to use when saving / reading permissions.
     */
    public function getAuthName()
    {
        return __CLASS__;
    }
}