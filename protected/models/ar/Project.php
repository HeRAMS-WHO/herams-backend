<?php

declare(strict_types=1);

namespace prime\models\ar;

use League\ISO3166\ISO3166;
use prime\behaviors\AuditableBehavior;
use prime\components\ActiveQuery as ActiveQuery;
use prime\components\Link;
use prime\interfaces\HeramsResponseInterface;
use prime\interfaces\project\ProjectForTabMenuInterface;
use prime\interfaces\RequestableInterface;
use prime\models\ActiveRecord;
use prime\models\ar\limesurvey\Project as LimesurveyProject;
use prime\models\ar\surveyjs\Project as SurveyJsProject;
use prime\objects\enums\ProjectStatus;
use prime\objects\enums\ProjectType;
use prime\objects\enums\ProjectVisibility;
use prime\objects\HeramsCodeMap;
use prime\objects\HeramsSubject;
use prime\objects\LanguageSet;
use prime\objects\Locale;
use prime\queries\FacilityQuery;
use prime\queries\WorkspaceQuery;
use prime\validators\EnumValidator;
use prime\validators\ExistValidator;
use prime\values\ProjectId;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\db\Expression;
use yii\db\ExpressionInterface;
use yii\db\Query;
use yii\helpers\Url;
use yii\validators\BooleanValidator;
use yii\validators\DefaultValueValidator;
use yii\validators\InlineValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\UniqueValidator;
use yii\web\Linkable;

/**
 * Class Project
 *
 * Attributes

 * @property string $country
 * @property string|null $created_at
 * @property boolean $hidden
 * @property array<string, array<string, string>> $i18n
 * @property int $id
 * @property list<string> $languages
 * @property float $latitude
 * @property float $longitude
 * @property boolean $manage_implies_create_hf
 * @property array $overrides
 * @property int $status
 * @property string $title
 * @property array<string, string> $typemap
 * @property string|null $updated_at
 * @property string $visibility
 *
 * Virtual fields
 * @property-read int $contributorCount
 * @property-read int $contributorPermissionCount
 * @property-read int $facilityCount
 * @property-read string $latestDate
 * @property-read int $pageCount
 * @property-read ProjectType $type
 * @property-read int $workspaceCount
 *
 * Relations
 * @property-read Page[] $mainPages
 * @property-read Page[] $pages
 * @property-read SurveyInterface $survey
 * @property-read Workspace[] $workspaces
 * @property-read Survey $adminSurvey
 *
 * @mixin VirtualFieldBehavior
 */
class Project extends ActiveRecord implements Linkable, RequestableInterface, ProjectForTabMenuInterface
{
    public const VISIBILITY_PUBLIC = 'public';

    public const VISIBILITY_PRIVATE = 'private';

    public const VISIBILITY_HIDDEN = 'hidden';

    public const STATUS_ONGOING = 0;

    public const STATUS_BASELINE = 1;

    public const STATUS_TARGET = 2;

    public const STATUS_EMERGENCY_SPECIFIC = 3;

    public const PROGRESS_ABSOLUTE = 'absolute';

    public const PROGRESS_PERCENTAGE = 'percentage';

    public function attributeHints(): array
    {
        return [
            'country' => \Yii::t('app', 'Only countries with an ISO3166 Alpha-3:wq code are listed'),
            'manage_implies_create_hf' => \Yii::t('app', 'When enabled anyone with the manage data permission will be allowed to create new facilities'),
            'name_code' => \Yii::t('app', 'Question code containing the name (case sensitive)'),
            'status' => \Yii::t('app', 'Project status is shown on the world map'),
            'type_code' => \Yii::t('app', 'Question code containing the type (case sensitive)'),
            'typemap' => \Yii::t('app', 'Map facility types for use in the world map'),
        ];
    }

    public static function instantiate($row): static|LimesurveyProject|SurveyJsProject
    {
        if (isset($row['base_survey_eid'])) {
            return new LimesurveyProject();
        } else {
            return new SurveyJsProject();
        }
    }

    public function beforeSave($insert): bool
    {
        $this->overrides = array_filter($this->overrides);
        return parent::beforeSave($insert);
    }

    public function behaviors(): array
    {
        return [
            AuditableBehavior::class,
            'virtualFields' => [
                'class' => VirtualFieldBehavior::class,
                'virtualFields' => self::virtualFields(),
            ],
        ];
    }

    public function exportDashboard(): array
    {
        $pages = [];
        foreach ($this->mainPages as $page) {
            $pages[] = $page->export();
        }
        return $pages;
    }

    public function extraFields(): array
    {
        $result = parent::extraFields();
        $result['subjectAvailabilityCounts'] = 'subjectAvailabilityCounts';
        $result['functionalityCounts'] = 'functionalityCounts';
        $result['typeCounts'] = 'typeCounts';
        $result['coordinatorName'] = static fn (self $project) => implode(', ', $project->getLeads());
        $result['statusText'] = 'statusText';

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
        foreach (['overrides', 'typemap', 'title', 'contributorPermissionCount'] as $hidden) {
            unset($fields[$hidden]);
        }
        return $fields;
    }

    public static function find(): ActiveQuery
    {
        return new ActiveQuery(get_called_class());
    }

    public function getLanguageSet(): LanguageSet
    {
        if (empty($this->languages)) {
            return LanguageSet::fullSet();
        } else {
            return LanguageSet::from($this->languages);
        }
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

        return User::find()->andWhere([
            'id' => $permissionQuery->select('source_id'),
        ])->all();
    }

    public function getLinks(): array
    {
        $result = [];
        $result[Link::REL_SELF] = Url::to([
            'project/view',
            'id' => $this->id,
        ]);
        $result['summary'] = Url::to([
            'project/summary',
            'id' => $this->id,
        ]);

        if (\Yii::$app->user->can(Permission::PERMISSION_READ, $this)) {
            if ($this->getOverride('dashboard')) {
                $result['dashboard'] = new Link([
                    'title' => \Yii::t('app', 'Dashboard'),
                    'type' => 'text/html',
                    'href' => Url::to([
                        '/project/external-dashboard',
                        'id' => $this->id,
                    ]),
                ]);
            } elseif ($this->getMainPages()->exists()) {
                $result['dashboard'] = new Link([
                    'title' => \Yii::t('app', 'Dashboard'),
                    'type' => 'text/html',
                    'href' => Url::to([
                        '/project/view',
                        'id' => $this->id,
                    ]),
                ]);
            }
        }

        $result['workspaces'] = new Link([
            'title' => \Yii::t('app', 'Workspaces'),
            'type' => 'text/html',
            'href' => Url::to(
                [
                    '/project/workspaces',
                    'id' => $this->id,
                ]
            ),
        ]);
        return $result;
    }

    public function getMainPages(): ActiveQuery
    {
        return $this->getPages()->andWhere([
            'parent_id' => null,
        ])->orderBy('sort');
    }

    public function getMap(): HeramsCodeMap
    {
        return new HeramsCodeMap();
    }

    public function getOverride(string $name): mixed
    {
        return $this->overrides[$name] ?? null;
    }

    public function getPages(): ActiveQuery
    {
        return $this->hasMany(Page::class, [
            'project_id' => 'id',
        ])
            ->inverseOf('project')
            // First sort by parent_id ?? id to "group" by page id
            // Secondly order by parent_id to make sure the actual parent is first
            // Last order by the sorting column
            ->orderBy([
                'COALESCE([[parent_id]], [[id]])' => SORT_ASC,
                'parent_id' => SORT_ASC,
                'sort' => SORT_ASC,
            ]);
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

    public function getStatusText(): string
    {
        return ProjectStatus::from($this->status)->label();
    }

    public function getWorkspaces(): WorkspaceQuery
    {
        return $this->hasMany(Workspace::class, [
            'project_id' => 'id',
        ])->inverseOf('project');
    }

    public function getFacilities(): FacilityQuery
    {
        return $this->hasMany(Facility::class, [
            'workspace_id' => 'id'
        ])->via('workspaces');
    }

    public function init(): void
    {
        parent::init();

        $this->overrides = [];
        $this->status = self::STATUS_ONGOING;
    }

    public function isHidden(): bool
    {
        return $this->visibility === self::VISIBILITY_HIDDEN;
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'admin_survey_id' => \Yii::t('app', 'Admin survey'),
            'base_survey_eid' => \Yii::t('app', 'Survey'),
            'country' => \Yii::t('app', 'Country'),
            'data_survey_id' => \Yii::t('app', 'Data survey'),
            'hidden' => \Yii::t('app', 'Hidden'),
            'i18n' => \Yii::t('app', 'Translated attributes'),
            'languages' => \Yii::t('app', 'Active languages'),
            'latitude' => \Yii::t('app', 'Latitude'),
            'longitude' => \Yii::t('app', 'Longitude'),
            'manage_implies_create_hf' => \Yii::t('app', 'Manage data implies creating facilities'),
            'overrides' => \Yii::t('app', 'Overrides'),
            'status' => \Yii::t('app', 'Status'),
            'typemap' => \Yii::t('app', 'Typemap'),
            'visibility' => \Yii::t('app', 'Visibility'),
        ]);
    }

    public function manageWorkspacesImpliesCreatingFacilities(): bool
    {
        return (bool) $this->manage_implies_create_hf;
    }

    public function rules(): array
    {
        return [
            [['title'], RequiredValidator::class],
            [['title'], UniqueValidator::class],
            [['base_survey_eid'],
                NumberValidator::class,
                'integerOnly' => true,
            ],
            [['hidden'], BooleanValidator::class],
            [['latitude', 'longitude'],
                NumberValidator::class,
                'integerOnly' => false,
            ],
            [['languages'],
                RangeValidator::class,
                'range' => Locale::keys(),
                'allowArray' => true,
            ],
            [['typemap', 'overrides', 'i18n'], function ($attribute) {
                if (! is_array($this->$attribute)) {
                    $this->addError($attribute, \Yii::t('app', '{attribute} must be an array.', [
                        'attribute' => $this->getAttributeLabel($attribute),
                    ]));
                }
            }],
            //            [['status'],
            //                EnumValidator::class,
            //                'enumClass' => ProjectStatus::class,
            //            ],
            [['visibility'],
                EnumValidator::class,
                'enumClass' => ProjectVisibility::class,
            ],
            [['country'], function () {
                $data = new ISO3166();
                try {
                    $data->alpha3($this->country);
                } catch (\Throwable $t) {
                    $this->addError('country', $t->getMessage());
                }
            }],
            [['country'],
                DefaultValueValidator::class,
                'value' => null,
            ],
            [['manage_implies_create_hf'], BooleanValidator::class],
            [['admin_survey_id', 'data_survey_id'],
                ExistValidator::class,
                'targetClass' => Survey::class,
                'targetAttribute' => 'id',
            ],
            [['data_survey_id', 'admin_survey_id', 'base_survey_eid'],
                function (string $attribute, null|array $params, InlineValidator $validator) {
                    if (empty($this->base_survey_eid) && (empty($this->admin_survey_id) || empty($this->data_survey_id))) {
                        $this->addError(
                            $attribute,
                            \Yii::t(
                                'app',
                                'Either {baseSurveyEid} or {adminSurveyId} and {dataSurveyId} must be set.',
                                [
                                    'baseSurveyEid' => $this->getAttributeLabel('base_survey_eid'),
                                    'adminSurveyId' => $this->getAttributeLabel('admin_survey_id'),
                                    'dataSurveyId' => $this->getAttributeLabel('data_survey_id'),
                                ]
                            )
                        );
                    }
                },
                'skipOnEmpty' => false,
            ],
        ];
    }

    private static function virtualFields(): array
    {
        return [
            'latestDate' => [
                VirtualFieldBehavior::GREEDY => Facility::find()->limit(1)->select('max([[latest_date]])')
                    ->andWhere([
                        'workspace_id' => Workspace::find()->select('id')->andWhere([
                            'project_id' => new Expression(self::tableName() . '.[[id]]'),
                        ]),

                    ]),
                VirtualFieldBehavior::LAZY => static fn (self $model): ?string
                    => $model->getFacilities()->select('max([[latest_date]])')->scalar(),
            ],
            'workspaceCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::GREEDY => $workspaceCountGreedy = Workspace::find()->limit(1)->select('count(*)')
                    ->where([
                        'project_id' => new Expression(self::tableName() . '.[[id]]'),
                    ]),
                VirtualFieldBehavior::LAZY => static fn (self $model) => $model->getWorkspaces()->count(),

            ],
            'pageCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::GREEDY => Page::find()->limit(1)->select('count(*)')
                    ->where([
                        'project_id' => new Expression(self::tableName() . '.[[id]]'),
                    ]),
                VirtualFieldBehavior::LAZY => static fn (self $model) => $model->getMainPages()->count(),
            ],
            'facilityCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::GREEDY => Facility::find()->andWhere([
                    'workspace_id' => Workspace::find()->select('id')
                        ->where([
                            'project_id' => new Expression(self::tableName() . '.[[id]]'),
                        ]),
                ])->select('count(*)'),
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    return Facility::find()->andWhere([
                        'workspace_id' => $model->getWorkspaces()->select('id')
                            ->where([
                                'project_id' => $model->id,
                            ]),
                    ])->count();
                },
            ],
            'responseCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::GREEDY => SurveyResponse::find()->andWhere([
                    'survey_id' => new Expression(self::tableName() . '.[[data_survey_id]]'),
                    'facility_id' => Facility::find()->andWhere([
                        'workspace_id' => Workspace::find()->select('id')
                            ->where([
                                'project_id' => new Expression(self::tableName() . '.[[id]]'),
                            ]),
                    ])->select('id')
                ])->select('count(*)'),
//                VirtualFieldBehavior::LAZY => static fn (self $model) => SurveyResponse::find()->andWhere([
//                    'survey_id' => $model->data_survey_id,
//                    'workspace_id' => $this->getWorkspaces()->select('id')
//                ])->count()
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
            'contributorPermissionCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::GREEDY => $contributorPermissionCountGreedy = Permission::find()->where([
                    'target' => Workspace::class,
                    'target_id' => Workspace::find()->select('id')
                        ->where([
                            'project_id' => new Expression(self::tableName() . '.[[id]]'),
                        ]),
                    'source' => User::class,
                ])->select('count(distinct [[source_id]])'),
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    return (int) Permission::find()->where([
                        'target' => Workspace::class,
                        'target_id' => $model->getWorkspaces()->select('id'),
                        'source' => User::class,
                    ])->count('distinct [[source_id]]');
                },
            ],
            'contributorCount' => [
                VirtualFieldBehavior::GREEDY => (function () use ($contributorPermissionCountGreedy, $workspaceCountGreedy): ExpressionInterface {
                    $result = new Query();
                    $permissionCount = self::getDb()->queryBuilder->buildExpression($contributorPermissionCountGreedy, $result->params);
                    $workspaceCount = self::getDb()->queryBuilder->buildExpression($workspaceCountGreedy, $result->params);

                    $result->addParams([
                        ':ccpath' => '$.contributorCount',
                    ]);
                    $result->select(new Expression("coalesce(cast(json_unquote(json_extract([[overrides]], :ccpath)) as unsigned), greatest($permissionCount, $workspaceCount))"));
                    return $result;
                })(),
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    return $model->getOverride('contributorCount') ?? max($model->contributorPermissionCount, $model->workspaceCount);
                },
            ],
        ];
    }

    public function getTitle(): string
    {
        return $this->getAttribute('title');
    }

    public function getRoute(): array
    {
        return [
            'project/update',
            'id' => $this->id,
        ];
    }

    public function getProjectTitle(): string
    {
        return $this->getTitle();
    }

    public static function tableName()
    {
        return '{{%project}}';
    }

    public function getLabel(): string
    {
        return $this->title;
    }

    public function getId(): ProjectId
    {
        return new ProjectId($this->getAttribute('id'));
    }

    public function getWorkspaceCount(): int
    {
        return $this->getVirtualField('workspaceCount');
    }

    public function getPermissionSourceCount(): int
    {
        return $this->getVirtualField('permissionSourceCount');
    }
}
