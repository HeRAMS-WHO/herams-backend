<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery as ActiveQuery;
use prime\components\LimesurveyDataProvider;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ActiveRecord;
use prime\models\forms\ResponseFilter;
use prime\objects\HeramsCodeMap;
use prime\queries\ResponseQuery;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use SamIT\LimeSurvey\Interfaces\WritableTokenInterface;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\db\Expression;
use yii\db\Query;
use yii\validators\ExistValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

/**
 * Class Workspace
 * @package prime\models
 *
 * Attributes
 * @property string $created
 * @property int $id
 * @property string $title
 * @property string $token
 * @property int $tool_id
 *
 * Virtual fields
 * @property int $contributorCount
 * @property int $facilityCount
 * @property ?string $latestUpdate
 * @property int $permissionSourceCount
 * @property int $responseCount
 *
 * Relations
 * @property-read User $owner
 * @property-read Project $project
 * @property-read HeramsResponseInterface[] $responses
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
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace) {
                            return $workspace->getResponses()->orderBy(['last_updated' => SORT_DESC])->limit(1)
                                    ->one()->last_updated ?? null;
                        },
                        VirtualFieldBehavior::GREEDY => Response::find()
                            ->limit(1)->select('max(last_updated)')
                            ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')])
                    ],
                    'facilityCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace) {
                            $filter = new ResponseFilter(null, new HeramsCodeMap());
                            return (int) $filter->filterQuery($workspace->getResponses())->count();
                        },
                        VirtualFieldBehavior::GREEDY => Response::find()
                            ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')])
                            ->select('count(distinct hf_id)'),
                    ],
                    'contributorCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::LAZY => static function (self $model): int {
                            return (int) Permission::find()->where([
                                'target' => Workspace::class,
                                'target_id' => $model->id,
                                'source' => User::class,
                            ])->count('distinct [[source_id]]');
                        },
                        VirtualFieldBehavior::GREEDY => Permission::find()->where([
                            'target' => Workspace::class,
                            'target_id' => new Expression(self::tableName() . '.[[id]]'),
                            'source' => User::class,
                        ])->select('count(distinct [[source_id]])')
                    ],
                    'permissionSourceCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::LAZY => static function (self $model): int {
                            return (int) $model->getPermissions()->count('distinct source_id');
                        },
                        VirtualFieldBehavior::GREEDY => Permission::find()->limit(1)->select('count(distinct source_id)')
                            ->where([
                                'source' => User::class,
                                'target' => self::class,
                                'target_id' => new Expression(self::tableName() . '.[[id]]')
                            ]),
                    ],
                    'responseCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace) {
                            return $workspace->getResponses()->count();
                        },
                        VirtualFieldBehavior::GREEDY => Response::find()->limit(1)->select('count(*)')
                            ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')]),
                    ]
                ]
            ]
        ];
    }

    public static function find(): WorkspaceQuery
    {
        return new WorkspaceQuery(self::class);
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'tool_id'])->inverseOf('workspaces');
    }

    public function isTransactional($operation)
    {
        return true;
    }

    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'project.title' => \Yii::t('app.model.workspace', 'Project'),
            'latestUpdate' => \Yii::t('app.model.workspace', 'Latest update'),
            'tool_id' => \Yii::t('app.model.workspace', 'Project'),
            'closed' => \Yii::t('app.model.workspace', 'Closed'),
            'token' => \Yii::t('app.model.workspace', 'Token'),
            'contributorCount' => \Yii::t('app.model.workspace', 'Contributors'),
            'facilityCount' => \Yii::t('app.model.workspace', 'Facilities'),
            'responseCount' => \Yii::t('app.model.workspace', 'Responses')
        ]);
    }


    public function rules()
    {
        return [
            [['title', 'tool_id'], RequiredValidator::class],
            [['title'], StringValidator::class, 'min' => 1],
            [['tool_id'], ExistValidator::class, 'targetClass' => Project::class, 'targetAttribute' => 'id'],
            [['tool_id'], NumberValidator::class],
            [['token'], UniqueValidator::class, 'filter' => function (Query $query) {
                $query->andWhere(['tool_id' => $this->tool_id]);
            }],
        ];
    }

    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);
        if ($result && empty($this->getAttribute('token'))) {
                // Attempt creation of a token.
                $token = $this->getLimesurveyDataProvider()->createToken($this->project->base_survey_eid, app()->security->generateRandomString(15));

                $token->setValidFrom(null);
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

            $token->setValidFrom(null);
            $token->save();
            $this->_token = $token;
        }
        return $this->_token;
    }

    /**
     * @return User[]
     */
    public function getLeads(): array
    {
        $permissionQuery = Permission::find()->andWhere([
            'target' => self::class,
            'target_id' => $this->id,
            'source' => User::class,
            'permission' => Permission::ROLE_LEAD
        ]);

        $result = User::find()->andWhere(['id' => $permissionQuery->select('source_id')])->all();

        return !empty($result) ? $result : $this->project->getLeads();
    }

    public function getLimesurveyDataProvider(): LimesurveyDataProvider
    {
        return \Yii::$app->get('limesurveyDataProvider');
    }

    public function getSurveyUrl(bool $canWrite = false, ?bool $canDelete = null): string
    {
        return $this->getLimesurveyDataProvider()->getUrl(
            $this->project->base_survey_eid,
            [
                'token' => $this->getAttribute('token'),
                'newtest' => 'Y',
                'lang' => \Yii::$app->language,
                'createButton' => 0,
                'seamless' => 1,
                'deleteButton' => $canDelete ?? $canWrite,
                'editButton' => $canWrite,
                'copyButton' => $canWrite
            ]
        );
    }

    public function scenarios()
    {
        $result = parent::scenarios();
        $result[self::SCENARIO_DEFAULT][] = '!tool_id';
        return $result;
    }

    public function getResponses(): ResponseQuery
    {
        return $this->hasMany(Response::class, [
            'workspace_id' => 'id',
        ])->inverseOf('workspace');
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

        return array_merge(['' => \Yii::t('app', 'Create new token')], $result);
    }
}
