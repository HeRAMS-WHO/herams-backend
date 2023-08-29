<?php

declare(strict_types=1);

namespace herams\common\models;

use Carbon\Carbon;
use herams\common\domain\facility\Facility;
use herams\common\domain\favorite\Favorite;
use herams\common\domain\user\User;
use herams\common\enums\UserPermissions;
use herams\common\interfaces\ConditionallyDeletable;
use herams\common\interfaces\RequestableInterface;
use herams\common\queries\ActiveQuery as ActiveQuery;
use herams\common\queries\FacilityQuery;
use herams\common\queries\WorkspaceQuery;
use herams\common\traits\LocalizedReadTrait;
use herams\common\values\ProjectId;
use herams\common\values\UserId;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\validators\ExistValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;
use function iter\map;

/**
 * Attributes
 * @property string|null $created_date
 * @property array<string, array<string, string>> $i18n
 * @property int $id
 * @property string $title
 * @property string $token
 * @property int $project_id
 * @property array|null $data
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
class Workspace extends ActiveRecord implements RequestableInterface, ConditionallyDeletable
{
    use LocalizedReadTrait;

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => 'last_modified_date',
                'createdAtAttribute' => 'created_date',
                'value' => fn () => Carbon::now(),
            ],
            BlameableBehavior::class => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => 'last_modified_by',
                'createdByAttribute' => 'created_by',
            ],
            'virtualFields' => [
                'class' => VirtualFieldBehavior::class,
                'virtualFields' => [
                    'leadNames' => [
                        VirtualFieldBehavior::GREEDY => (function () {
                            $permissionQuery = Permission::find()->andWhere([
                                'target' => self::class,
                                'target_id' => new Expression(self::tableName() . '.[[id]]'),
                                'source' => User::class,
                                'permission' => Permission::ROLE_LEAD,
                            ]);

                            return User::find()->andWhere([
                                'id' => $permissionQuery->select('source_id'),
                            ])->select(new Expression("GROUP_CONCAT(name SEPARATOR ', ')"));
                        })(),
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace): string {
                            return \iter\join(', ', map(fn (User $user) => $user->name, $workspace->getLeads()));
                        },
                    ],

                    // This is a clunky trick since we depend on the current user...
                    'favorite_id' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => static fn () => Favorite::find()
                            ->workspaces()
                            ->user(new UserId(\Yii::$app->user->id))
                            ->andWhere([
                                'target_id' => new Expression(self::tableName() . '.[[id]]'),
                            ])
                            ->select('min(id)'),
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace): null|int {
                            return Favorite::find()->workspaces()->andWhere([
                                'target_id' => $workspace->id,
                            ])->select('id')->limit(1)->one()?->id;
                        },
                    ],
                    'projectTitle' => [
                        VirtualFieldBehavior::GREEDY => Project::find()
                            ->limit(1)->select('title')
                            ->where([
                                'id' => new Expression(self::tableName() . '.[[project_id]]'),
                            ]),
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace): null|string {
                            return $workspace->getProject()->limit(1)->one()->title ?? null;
                        },
                    ],
                    'latestUpdate' => [
                        VirtualFieldBehavior::GREEDY => Facility::find()
                            ->limit(1)->select('max(date_of_update)')
                            ->where([
                                'workspace_id' => new Expression(self::tableName() . '.[[id]]'),
                            ]),
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace) {
                            return $workspace->getFacilities()->orderBy([
                                'date_of_update' => SORT_DESC,
                            ])->limit(1)
                                ->one()->date_of_update ?? null
                            ;
                        },
                    ],
                    'facilityCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Facility::find()
                            ->andWhere([
                                'workspace_id' => new Expression(self::tableName() . '.[[id]]'),
                            ])->andWhere([
                                'or',
                                ['!=', 'status', 'Deleted'],
                                ['IS', 'status', null],
                            ])
                            ->select([
                                'count' => 'count(*)',
                            ]),
                        VirtualFieldBehavior::LAZY => static function (Workspace $workspace) {
                            return (int) $workspace->getFacilities()->andWhere([
                                'or',
                                ['!=', 'status', 'Deleted'],
                                ['IS', 'status', null],
                            ])->count();
                        },
                    ],
                    'contributorCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Permission::find()->where([
                            'target' => UserPermissions::CAN_ACCESS_TO_WORKSPACE->value,
                            'target_id' => new Expression(self::tableName() . '.[[id]]'),
                        ])->select('count(distinct [[source_id]])'),
                        VirtualFieldBehavior::LAZY => static function (self $model): int {
                            return (int) Permission::find()->where([
                                'target' => UserPermissions::CAN_ACCESS_TO_WORKSPACE->value,
                                'target_id' => $model->id,
                            ])->count('distinct [[source_id]]');
                        },
                    ],
                    'permissionSourceCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Permission::find()->limit(1)->select('count(distinct source_id)')
                            ->where([
                                'source' => User::class,
                                'target' => self::class,
                                'target_id' => new Expression(self::tableName() . '.[[id]]'),
                            ]),
                        VirtualFieldBehavior::LAZY => static function (self $model): int {
                            return (int) $model->getPermissions()->count('distinct source_id');
                        },
                    ],
                    'responseCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => SurveyResponse::find()
                            ->limit(1)
                            ->select('count(*)')
                            ->where([
                                'facility_id' => Facility::find()
                                    ->select('id')
                                    ->andWhere([
                                        'workspace_id' => new Expression(self::tableName() . '.[[id]]'),
                                    ]),
                            ])->andWhere([
                                'or',
                                ['!=', 'status', 'Deleted'],
                                ['IS', 'status', null],
                            ]),
                        VirtualFieldBehavior::LAZY => static fn (Workspace $workspace): int => (int) $workspace->getResponses()->count(),

                    ],

                    'date_of_update' => [

                        VirtualFieldBehavior::LAZY => static fn (self $workspace) => SurveyResponse::find()
                            ->where([
                                'facility_id' => Facility::find()
                                    ->select('id')
                                    ->andWhere([
                                        'workspace_id' => $workspace->id,
                                    ]),
                            ])->andWhere([
                                'or',
                                ['!=', 'status', 'Deleted'],
                                ['IS', 'status', null],
                            ])
                            ->orderBy('date_of_update DESC')->limit(1)->one()->date_of_update ?? null,
                    ],
                ],
            ],
        ];
    }

    public function getFacilities(): FacilityQuery
    {
        return $this->hasMany(Facility::class, [
            'workspace_id' => 'id',
        ]);
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
            'permission' => Permission::ROLE_LEAD,
        ]);

        $result = User::find()->andWhere([
            'id' => $permissionQuery->select('source_id'),
        ])->all();

        return ! empty($result) ? $result : $this->project->getLeads();
    }

    public function getPermissions(): ActiveQuery
    {
        return $this->hasMany(Permission::class, [
            'target_id' => 'id',
        ])
            ->andWhere([
                'target' => self::class,
            ]);
    }

    public static function find(): WorkspaceQuery
    {
        return new WorkspaceQuery(static::class);
    }

    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, [
            'id' => 'project_id',
        ])->inverseOf('workspaces');
    }

    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(SurveyResponse::class, [
            'id' => 'workspace_id',
        ])->via('facilities');
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
            [['title'],
                StringValidator::class,
                'min' => 1,
            ],
            [['date_of_update', 'created_date', 'created_by', 'last_modified_date', 'last_modified_by', 'status'], SafeValidator::class],
            [['project_id'],
                ExistValidator::class,
                'targetRelation' => 'project',
            ],
            [['i18n'], function ($attribute) {
                if (! is_array($this->$attribute)) {
                    $this->addError($attribute, \Yii::t('app', '{attribute} must be an array.', [
                        'attribute' => $this->getAttributeLabel($attribute),
                    ]));
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

    final public function getTitle(): string
    {
        return $this->getLocalizedAttribute('title', \Yii::$app->language, 'en') ?? "#{$this->id}";
    }

    public function getRoute(): array
    {
        return [
            'workspace/update',
            'id' => $this->id,
        ];
    }

    public function getProjectTitle(): string
    {
        return $this->getBehavior('virtualFields')->__get('projectTitle');
    }

    public function canBeDeleted(): bool
    {
        return ! $this->getFacilities()->exists();
    }

    public function getProjectId(): ProjectId
    {
        return new ProjectId($this->project_id);
    }

    public function extraFields(): array
    {
        $result = parent::extraFields();
        //        $result['subjectAvailabilityCounts'] = 'subjectAvailabilityCounts';
        //        $result['functionalityCounts'] = 'functionalityCounts';
        //        $result['typeCounts'] = 'typeCounts';
        //        $result['coordinatorName'] = static fn(self $project) => implode(', ', $project->getLeads());
        //        $result['statusText'] = 'statusText';

        return $result;
    }

    public function fields(): array
    {
        $fields = parent::fields();
        $fields['name'] = 'title';

        /** @var VirtualFieldBehavior $virtualFields */
        $virtualFields = $this->getBehavior('virtualFields');
        foreach ($virtualFields->virtualFields as $key => $definition) {
            $fields[$key] = $key;
        }
        return $fields;
    }
}
