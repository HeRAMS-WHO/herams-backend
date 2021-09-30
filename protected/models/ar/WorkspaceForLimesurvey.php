<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery;
use prime\components\LimesurveyDataProvider;
use prime\helpers\ArrayHelper;
use prime\models\forms\ResponseFilter;
use prime\objects\HeramsCodeMap;
use SamIT\LimeSurvey\Interfaces\WritableTokenInterface;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\db\Expression;
use yii\db\Query;
use yii\validators\UniqueValidator;

/**
 * This version of the workspace is separated since it is the "old" version.
 * When we transition to only use SurveyJS this class should have no usages anymore and can be deleted.
 *
 * Attributes
 * @property string $token
 */
class WorkspaceForLimesurvey extends Workspace
{
    /**
     * @var WritableTokenInterface
     */
    protected $_token;

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                    /**
                     * Since a project can only contain workspaces of 1 type (Limesurvey or SurveyJS), we do not need to worry about
                     * "combined case" behaviors, especially the greedy case.
                     */
                    VirtualFieldBehavior::class => [
                    'class' => VirtualFieldBehavior::class,
                    'virtualFields' => [
                        'latestUpdate' => [
                            VirtualFieldBehavior::GREEDY => ResponseForLimesurvey::find()
                                ->limit(1)->select('max(last_updated)')
                                ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')]),
                            VirtualFieldBehavior::LAZY => static function (WorkspaceForLimesurvey $workspace) {
                                return $workspace->getResponses()->orderBy(['last_updated' => SORT_DESC])->limit(1)
                                    ->one()->last_updated ?? null;
                            }
                        ],
                        'facilityCount' => [
                            VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                            VirtualFieldBehavior::GREEDY => (function () {
                                $responseQuery = ResponseForLimesurvey::find()
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
                            })(),
                            VirtualFieldBehavior::LAZY => static function (WorkspaceForLimesurvey $workspace) {
                                $filter = new ResponseFilter(null, new HeramsCodeMap());
                                return $filter->filterQuery($workspace->getResponses())->count()
                                    + $workspace->getFacilities()->count()

                                    ;
                            }
                        ],
                        'contributorCount' => [
                            VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                            VirtualFieldBehavior::GREEDY => Permission::find()->where([
                                'target' => WorkspaceForLimesurvey::class,
                                'target_id' => new Expression(self::tableName() . '.[[id]]'),
                                'source' => User::class,
                            ])->select('count(distinct [[source_id]])')
                            ,
                            VirtualFieldBehavior::LAZY => static function (self $model): int {
                                return (int) Permission::find()->where([
                                    'target' => WorkspaceForLimesurvey::class,
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
                            VirtualFieldBehavior::GREEDY => ResponseForLimesurvey::find()->limit(1)->select('count(*)')
                                ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')]),
                            VirtualFieldBehavior::LAZY => static function (WorkspaceForLimesurvey $workspace) {
                                return $workspace->getResponses()->count();
                            }
                        ]
                    ]
                    ]
            ]
        );
    }

    public static function find(): ActiveQuery
    {
        return parent::find()->andWhere(['not', ['token' => null]]);
    }

    public function isTransactional($operation)
    {
        return true;
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'closed' => \Yii::t('app.model.workspace', 'Closed'),
            'latestUpdate' => \Yii::t('app.model.workspace', 'Latest update'),
            'token' => \Yii::t('app.model.workspace', 'Token'),
            'contributorCount' => \Yii::t('app.model.workspace', 'Contributors'),
            'facilityCount' => \Yii::t('app.model.workspace', 'Facilities'),
            'responseCount' => \Yii::t('app.model.workspace', 'Responses')
        ]);
    }

    public function rules(): array
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['token'], UniqueValidator::class, 'filter' => function (Query $query) {
                    $query->andWhere(['tool_id' => $this->tool_id]);
                }],
            ]
        );
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

    public function setProjectId(int $id): void
    {
        $this->tool_id = $id;
    }
}
