<?php

declare(strict_types=1);

namespace prime\models\ar;

use prime\behaviors\AuditableBehavior;
use prime\components\ActiveQuery as ActiveQuery;
use prime\helpers\ArrayHelper;
use prime\interfaces\RequestableInterface;
use prime\models\ActiveRecord;
use prime\models\forms\ResponseFilter;
use prime\objects\HeramsCodeMap;
use prime\queries\FacilityQuery;
use prime\queries\ResponseForLimesurveyQuery;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\db\Expression;
use yii\db\Query;
use yii\validators\ExistValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Attributes
 * @property string|null $created_at
 * @property array<string, array<string, string>> $i18n
 * @property int $id
 * @property string $title
 * @property string $token
 * @property int $project_id
 * @property string|null $updated_at
 *
 * Virtual attributes
 * @property int $contributorCount
 * @property int $facilityCount
 * @property ?string $latestUpdate
 * @property int $permissionSourceCount
 * @property int $responseCount
 *
 * Relations
 * @property-read User $owner
 * @property-read Project $project
 */
class Workspace extends ActiveRecord implements RequestableInterface
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                AuditableBehavior::class,
                /**
                 * Since a project can only contain workspaces of 1 type (Limesurvey or SurveyJS), we do not need to worry about
                 * "combined case" behaviors, especially the greedy case.
                 */
                VirtualFieldBehavior::class => [
                    'class' => VirtualFieldBehavior::class,
                    'virtualFields' => [
                        'projectTitle' => [
                            VirtualFieldBehavior::GREEDY => Project::find()
                                ->limit(1)->select('title')
                                ->where(['id' => new Expression(self::tableName() . '.[[project_id]]')]),
                            VirtualFieldBehavior::LAZY => static function (Workspace $workspace): null|string {
                                return $workspace->getProject()->limit(1)->one()->title ?? null;
                            }
                        ],
                        'latestUpdate' => [
                            VirtualFieldBehavior::GREEDY => ResponseForLimesurvey::find()
                                ->limit(1)->select('max(last_updated)')
                                ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')]),
                            VirtualFieldBehavior::LAZY => static function (Workspace $workspace) {
                                return $workspace->getResponses()->orderBy(['updated_at' => SORT_DESC])->limit(1)
                                        ->one()->updated_at ?? null;
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
                            VirtualFieldBehavior::GREEDY => ResponseForLimesurvey::find()->limit(1)->select('count(*)')
                                ->where(['workspace_id' => new Expression(self::tableName() . '.[[id]]')]),
                            VirtualFieldBehavior::LAZY => static function (Workspace $workspace) {
                                return $workspace->getResponses()->count();
                            }
                        ]
                    ]
                ]
            ]
        );
    }

    public function getFacilities(): FacilityQuery
    {
        return $this->hasMany(Facility::class, ['workspace_id' => 'id']);
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

    public function getPermissions(): ActiveQuery
    {
        return $this->hasMany(Permission::class, ['target_id' => 'id'])
            ->andWhere(['target' => self::class]);
    }

    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, ['id' => 'project_id'])->inverseOf('workspaces');
    }

    public function getResponses(): ResponseForLimesurveyQuery
    {
        return $this->hasMany(ResponseForLimesurvey::class, ['workspace_id' => 'id'])->inverseOf('workspace');
    }

    public static function instantiate($row): ActiveRecord
    {
        // Single table inheritance: when we need a WorkspaceForLimesurvey instance,
        if (!empty($row['token'])) {
            return new WorkspaceForLimesurvey();
        }

        return parent::instantiate($row);
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'i18n' => \Yii::t('app.model.workspace', 'Project'),
            'project.title' => \Yii::t('app.model.workspace', 'Project'),
            'project_id' => \Yii::t('app.model.workspace', 'Project'),
        ]);
    }

    public function rules(): array
    {
        return [
            [['title', 'project_id'], RequiredValidator::class],
            [['title'], StringValidator::class, 'min' => 1],
            [['project_id'], ExistValidator::class, 'targetRelation' => 'project'],
            [['i18n'], function ($attribute) {
                if (!is_array($this->$attribute)) {
                    $this->addError($attribute, \Yii::t('app', '{attribute} must be an array.', ['attribute' => $this->getAttributeLabel($attribute)]));
                }
            }],
        ];
    }

    public function scenarios(): array
    {
        $result = parent::scenarios();
        $result[self::SCENARIO_DEFAULT][] = '!project_id';
        return $result;
    }

    public static function tableName(): string
    {
        return '{{%workspace}}';
    }

    public function getTitle(): string
    {
        return $this->getAttribute('title') ?? '';
    }

    public function getRoute(): array
    {
        return ['workspace/update', 'id' => $this->id];
    }

    public function getProjectTitle(): string
    {
        return $this->getBehavior(VirtualFieldBehavior::class)->__get('projectTitle');
    }
}
