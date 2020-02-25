<?php
declare(strict_types=1);

namespace prime\models\ar;

use Carbon\Carbon;
use prime\components\LimesurveyDataProvider;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ActiveRecord;
use prime\models\forms\ResponseFilter;
use prime\models\permissions\Permission;
use prime\objects\HeramsCodeMap;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use SamIT\LimeSurvey\Interfaces\WritableTokenInterface;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use SamIT\Yii2\VirtualFields\VirtualFieldQueryBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
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
 * @property int $id
 * @property \DateTimeImmutable $created
 *
 * @property HeramsResponseInterface[] $responses
 */
class Workspace extends ActiveRecord
{
    /**
     * @var WritableTokenInterface
     */
    protected $_token;

    public function getPermissions(): ActiveQuery
    {
        return $this->hasMany(Permission::class, ['target_id' => 'id'])
            ->andWhere(['target' => self::class]);
    }

    public function behaviors()
    {
        return [

            VirtualFieldBehavior::class => [
                'class' => VirtualFieldBehavior::class,
                'virtualFields' => [
                    'latestUpdate' => [
                        VirtualFieldBehavior::GREEDY => Response::find()
                            ->limit(1)->select('max(last_updated)')
                            ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')]),
                        VirtualFieldBehavior::LAZY => static function(Workspace $workspace) {
                            return $workspace->getResponses()->orderBy(['last_updated' => SORT_DESC])->limit(1)
                                ->one()->last_updated ?? null;
                        }
                    ],
                    'facilityCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Response::find()
                            ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')])
                            ->select('count(distinct hf_id)'),
                        VirtualFieldBehavior::LAZY => static function(Workspace $workspace) {
                            $filter = new ResponseFilter(null, new HeramsCodeMap());
                            return (int) $filter->filterQuery($workspace->getResponses())->count();
                        }
                    ],
                    'permissionCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Permission::find()->limit(1)->select('count(*)')
                        ->where(['target' => self::class, 'target_id' => new Expression(self::tableName() . '.[[id]]')]),
                        VirtualFieldBehavior::LAZY => static function(Workspace $workspace) {
                            return $workspace->getPermissions()->count();
                        }
                    ],
                    'responseCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Response::find()->limit(1)->select('count(*)')
                            ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')]),
                        VirtualFieldBehavior::LAZY => static function(Workspace $workspace) {
                            return $workspace->getResponses()->count();
                        }
                    ]
                ]
            ]
        ];
    }


    public static function find(): ActiveQuery
    {
        $result = new ActiveQuery(self::class);
        $result->attachBehaviors([
            VirtualFieldQueryBehavior::class => [
                'class' => VirtualFieldQueryBehavior::class
            ]
        ]);
        return $result;
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
            [['token'], UniqueValidator::class, 'filter' => function(Query $query) { $query->andWhere(['tool_id' => $this->tool_id]); }],
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

    public function  getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, [
            'workspace_id' => 'id',
        ])->inverseOf('workspace');
    }

    public function getResponseCount(): int
    {
        return (int) $this->getResponses()->count();
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

