<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery as ActiveQuery;
use prime\components\LimesurveyDataProvider;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ActiveRecord;
use prime\models\forms\ResponseFilter;
use prime\objects\HeramsCodeMap;
use prime\queries\FacilityQuery;
use prime\queries\ResponseQuery;
use prime\values\ProjectId;
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
                        VirtualFieldBehavior::GREEDY => Response::find()
                            ->limit(1)->select('max(last_updated)')
                            ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')]),
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace) {
                            return $workspace->getResponses()->orderBy(['last_updated' => SORT_DESC])->limit(1)
                                ->one()->last_updated ?? null;
                        }
                    ],
                    'facilityCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => (function () {
                            $responseQuery = Response::find()
                                ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')])
                                ->select(['count' => 'count(distinct hf_id)']);
                            $facilityQuery = Facility::find()
                                ->andWhere(['workspace_id' => new Expression(self::tableName() . '.[[id]]')])
                                ->select(['count' => 'count(*)']);

                            $responseQuery->union($facilityQuery);
                            $query = new Query();
                            $query->from(['sub' => $responseQuery]);
                            $query->select('sum(count)');
                            return $query;
                        })()



                            ,
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace) {
                            $filter = new ResponseFilter(null, new HeramsCodeMap());
                            return $filter->filterQuery($workspace->getResponses())->count()
                                + $workspace->getFacilities()->count()

                                ;
                        }
                    ],
                    'contributorCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Permission::find()->where([
                            'target' => Workspace::class,
                            'target_id' => new Expression(self::tableName() . '.[[id]]'),
                            'source' => User::class,
                        ])->select('count(distinct [[source_id]])')
                        ,
                        VirtualFieldBehavior::LAZY => static function (self $model): int {
                            return (int) Permission::find()->where([
                                'target' => Workspace::class,
                                'target_id' => $model->id,
                                'source' => User::class,
                            ])->count('distinct [[source_id]]');
                        }
                    ],
                    'permissionSourceCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Permission::find()->limit(1)->select('count(distinct source_id)')
                            ->where([
                                'source' => User::class,
                                'target' => self::class,
                                'target_id' => new Expression(self::tableName() . '.[[id]]')
                            ]),
                        VirtualFieldBehavior::LAZY => static function (self $model): int {
                            return (int) $model->getPermissions()->count('distinct source_id');
                        }
                    ],
                    'responseCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Response::find()->limit(1)->select('count(*)')
                            ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')]),
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace) {
                            return $workspace->getResponses()->count();
                        }
                    ]
                ]
            ]
        ];
    }

    public function getFacilities(): FacilityQuery
    {
        return $this->hasMany(Facility::class, ['workspace_id' => 'id']);
    }

    public static function find(): ActiveQuery
    {
        return new ActiveQuery(self::class);
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'tool_id'])->inverseOf('workspaces');
    }

    public function isTransactional($operation)
    {
        return true;
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
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


    public function rules(): array
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
        if ($result && empty($this->token)) {
                // Attempt creation of a token.
                $token = $this->getLimesurveyDataProvider()->createToken($this->project->base_survey_eid, app()->security->generateRandomString(15));

                $token->setValidFrom(null);
                $this->_token = $token;
                $this->setAttribute('token', $token->getToken());
                return $token->save();
        }
        return $result;
    }

    public function setProjectId(int $id): void
    {
        $this->tool_id = $id;
    }

    public function getLeads(): ActiveQuery
    {
        $permissionQuery = Permission::find()->andWhere([
            'target' => self::class,
            'target_id' => $this->id,
            'source' => User::class,
            'permission' => Permission::ROLE_LEAD
        ]);

        $userQuery = User::find()
            ->andWhere(['id' => $permissionQuery->select('source_id')]);
        $userQuery->multiple = true;
        return $userQuery;
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
}
