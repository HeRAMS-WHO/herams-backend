<?php

namespace prime\models\ar;

use app\queries\WorkspaceQuery;
use Carbon\Carbon;
use prime\components\LimesurveyDataProvider;
use prime\interfaces\AuthorizableInterface;
use prime\interfaces\FacilityListInterface;
use prime\interfaces\ResponseCollectionInterface;
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
use yii\validators\DateValidator;
use yii\validators\ExistValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

/**
 * Class Project
 * @package prime\models
 *
 * @property User $owner
 * @property Project $project
 * @property string $title
 * @property string $description
 * @property string $default_generator
 * @property string $country_iso_3
 * @property int $data_survey_eid The associated data survey.
 * @property int $tool_id
 * @property datetime $created
 * @property boolean $isClosed
 * @property Country $country
 * @property int $owner_id
 *
 * @method static WorkspaceQuery find()
 */
class Workspace extends ActiveRecord implements AuthorizableInterface
{
    use LoadOneAuthTrait;
    /**
     * @var WritableTokenInterface
     */
    protected $_token;

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'data_survey_eid' => \Yii::t('app', 'Data survey'),
            'owner_id' => \Yii::t('app', 'Owner')
        ]);
    }

    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'token' => 'Note that the first name and last name fieldOptions in the tokens will be overridden upon project creation!.'
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
        \Yii::beginProfile(__FUNCTION__, __CLASS__);
        $result = $this->getLimesurveyDataProvider()->getResponsesByToken($this->project->base_survey_eid, $this->getAttribute('token'));
        \Yii::endProfile(__FUNCTION__, __CLASS__);
        return $result;
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'tool_id']);
    }

    public function isTransactional($operation)
    {
        return true;
    }

    public function ownerOptions()
    {
        return ArrayHelper::map(User::find()->all(), 'id', 'name');
    }

    public function rules()
    {
        return [
            [['title', 'owner_id', 'tool_id'], RequiredValidator::class],
            [['title'], StringValidator::class, 'min' => 1],
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
        $result = parent::userCan($operation, $user) || ($operation === Permission::PERMISSION_READ);
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
                throw new \Exception("Failed to grant permission");
            }
        }
    }


    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);
        if ($result && empty($this->getAttribute('token'))) {
                // Attempt creation of a token.
                $token = $this->getLimesurveyDataProvider()->createToken($this->project->base_survey_eid, [
                    'token' => app()->security->generateRandomString(15)
                ]);

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
            $token = $this->getLimesurveyDataProvider()->getToken($this->project->base_survey_eid, $this->token);

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
            $this->project->base_survey_eid,
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

}

