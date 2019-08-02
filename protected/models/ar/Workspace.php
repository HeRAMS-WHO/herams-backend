<?php

namespace prime\models\ar;

use app\queries\WorkspaceQuery;
use Carbon\Carbon;
use prime\components\LimesurveyDataProvider;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ActiveRecord;
use prime\models\forms\ResponseFilter;
use prime\models\permissions\Permission;
use prime\traits\LoadOneAuthTrait;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use SamIT\LimeSurvey\Interfaces\WritableTokenInterface;
use yii\db\ActiveQuery;
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
 * @property int $tool_id
 * @property datetime $created
 *
 * @method static WorkspaceQuery find()
 * @property HeramsResponseInterface[] $heramsResponses
 */
class Workspace extends ActiveRecord
{
    use LoadOneAuthTrait;
    /**
     * @var WritableTokenInterface
     */
    protected $_token;

    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'token' => 'Note that the first name and last name fieldOptions in the tokens will be overridden upon project creation!.'
        ]);
    }

    public function getPermissions(): ActiveQuery
    {
        return $this->hasMany(Permission::class, ['target_id' => 'id'])
            ->andWhere(['target' => self::class]);
    }

    public function getPermissionParent(): ?ActiveRecord
    {
        return $this->project;
    }

    /**
     * @return ResponseInterface[]
     */
    public function getRawResponses()
    {
        return $this->getLimesurveyDataProvider()->getResponsesByToken($this->project->base_survey_eid, $this->getAttribute('token'));
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'tool_id'])->inverseOf('workspaces');
    }

    public function isTransactional($operation)
    {
        return true;
    }

    public function rules()
    {
        return [
            [['title', 'tool_id'], RequiredValidator::class],
            [['title'], StringValidator::class, 'min' => 1],
            [['tool_id'], ExistValidator::class, 'targetClass' => Project::class, 'targetAttribute' => 'id'],
            [['tool_id'], NumberValidator::class],
            ['token', UniqueValidator::class],
        ];
    }

    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);
        if ($result && empty($this->getAttribute('token'))) {
                // Attempt creation of a token.
                $token = $this->getLimesurveyDataProvider()->createToken($this->project->base_survey_eid, app()->security->generateRandomString(15));

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

    public function scenarios()
    {
        $result = parent::scenarios();
        $result[self::SCENARIO_DEFAULT][] = '!tool_id';
        return $result;
    }


    public function getIsClosed()
    {
        return isset($this->closed);
    }

    public function  getResponses()
    {
        return $this->hasMany(Response::class, [
            'workspace_id' => 'id',
        ])->inverseOf('workspace');
    }

    public function getFacilityCount(): int
    {
        $filter = new ResponseFilter(null, $this->project->getMap());
        return $filter->filterQuery($this->getResponses())->count();
    }

    public function getResponseCount(): int
    {
        return $this->getResponses()->count();
    }

    public function tokenOptions(): array
    {
        $limesurveyDataProvider = $this->getLimesurveyDataProvider();
        $usedTokens = $this->project->getWorkspaces()->select(['token'])->indexBy('token')->column();

        $tokens = $limesurveyDataProvider->getTokens($this->project->base_survey_eid);

        $result = [];
        /** @var TokenInterface $token */
        foreach ($tokens as $token) {
            if (isset($usedTokens[$token->getToken()])) {
                continue;
            }
            if (!empty($token->getToken())) {
                $result[$token->getToken()] = "{$token->getFirstName()} {$token->getLastName()} ({$token->getToken()}) " . implode(
                        ', ',
                        array_filter($token->getCustomAttributes())
                    );
            }
        }
        asort($result);

        return array_merge(['' => 'Create new token'], $result);
    }

}

