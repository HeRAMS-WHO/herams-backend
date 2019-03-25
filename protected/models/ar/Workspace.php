<?php

namespace prime\models\ar;

use app\queries\WorkspaceQuery;
use Carbon\Carbon;
use prime\components\LimesurveyDataProvider;
use prime\interfaces\AuthorizableInterface;
use prime\interfaces\FacilityListInterface;
use prime\interfaces\ProjectInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\WorkspaceInterface;
use prime\lists\FacilityList;
use prime\models\ActiveRecord;
use prime\models\Country;
use prime\models\permissions\Permission;
use prime\objects\ResponseCollection;
use prime\traits\LoadOneAuthTrait;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\WritableTokenInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\validators\DateValidator;
use yii\validators\ExistValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use yii\web\Linkable;

/**
 * Class Project
 * @package prime\models
 *
 * @property User $owner
 * @property Project $tool
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
 * @method static WorkspaceQuery find()
 */
class Workspace extends ActiveRecord implements AuthorizableInterface, WorkspaceInterface, Linkable
{
    use LoadOneAuthTrait;
    /**
     * @var WritableTokenInterface
     */
    protected $_token;

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'tool_id' => \Yii::t('app', 'Tool'),
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

    /**
     * Return the name of the locality. If locality_name isn't set, return name of the country
     * @return string
     */
    public function getLocality()
    {
        // Quick fix
        return "Unknown";
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

    // Cache for getResponses();
    private $_responses;
    
    /**
     * @return ResponseCollectionInterface
     */
    public function getResponses()
    {
        if (!isset($this->_responses)) {
            $this->_responses = new ResponseCollection();
            foreach ($this->getLimesurveyDataProvider()->getResponses($this->tool->base_survey_eid) as $response) {
                if ($response->getData()['token'] !== $this->token) {
                    continue;
                }
                $this->_responses->append($response);
            }
        }
        return $this->_responses;
    }

    public function getTool()
    {
        return $this->hasOne(Project::class, ['id' => 'tool_id']);
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
            [['title', 'owner_id', 'tool_id'], RequiredValidator::class],
            [['title'], StringValidator::class],
            [['owner_id'], ExistValidator::class, 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['tool_id'], ExistValidator::class, 'targetClass' => Project::class, 'targetAttribute' => 'id'],
            [['tool_id'], NumberValidator::class],
            [['closed'], DateValidator::class,'format' => 'php:Y-m-d H:i:s', 'skipOnEmpty' => true],
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
        if ($result && empty($this->getAttribute('token'))) {
                // Attempt creation of a token.
                $token = $this->getLimesurveyDataProvider()->createToken($this->tool->base_survey_eid, [
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
            $token = $this->getLimesurveyDataProvider()->getToken($this->tool->base_survey_eid, $this->token);

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


    public function getLimesurveyDataProvider(): LimesurveyDataProvider
    {
        return \Yii::$app->get('limesurveyDataProvider');
    }

    public function getSurveyUrl()
    {
        return $this->getLimesurveyDataProvider()->getUrl(
            $this->tool->base_survey_eid,
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

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }

    public function getId(): string
    {
        return $this->getAttribute('id');
    }

    public function getName(): string
    {
        return $this->getAttribute('title');
    }

    public function getProject(): ProjectInterface
    {
        return $this->tool;
    }

    public function getFacilities(): FacilityListInterface
    {
        $facilities = [];
        /** @var ResponseInterface $response */
        foreach($this->getResponses() as $response) {
            if (isset($response->getData()['HF1'])) {
                $name = $response->getData()['HF1'];

                $facilities[$name] = new Facility($name);
            }

        }
        return new FacilityList([]);
    }

    /**
     * Returns a list of links.
     *
     * Each link is either a URI or a [[Link]] object. The return value of this method should
     * be an array whose keys are the relation names and values the corresponding links.
     *
     * If a relation name corresponds to multiple links, use an array to represent them.
     *
     * For example,
     *
     * ```php
     * [
     *     'self' => 'http://example.com/users/1',
     *     'friends' => [
     *         'http://example.com/users/2',
     *         'http://example.com/users/3',
     *     ],
     *     'manager' => $managerLink, // $managerLink is a Link object
     * ]
     * ```
     *
     * @return array the links
     */
    public function getLinks()
    {
        return [
            'self' => Url::to(['workspace/view', 'id' => $this->id], true),
            'facilities' => Url::to(['facility/index', 'workspace_id' => $this->id], true),
            'project' => Url::to(['project/view', 'id' => $this->tool_id], true),
        ];
    }
}

