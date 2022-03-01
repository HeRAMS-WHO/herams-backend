<?php

declare(strict_types=1);

namespace prime\models\ar;

use League\ISO3166\ISO3166;
use prime\behaviors\AuditableBehavior;
use prime\components\ActiveQuery as ActiveQuery;
use prime\components\LimesurveyDataProvider;
use prime\components\Link;
use prime\interfaces\HeramsResponseInterface;
use prime\interfaces\RequestableInterface;
use prime\models\ActiveRecord;
use prime\models\ar\limesurvey\Project as LimesurveyProject;
use prime\models\ar\surveyjs\Project as SurveyJsProject;
use prime\objects\enums\Language;
use prime\objects\enums\ProjectStatus;
use prime\objects\enums\ProjectType;
use prime\objects\enums\ProjectVisibility;
use prime\objects\HeramsCodeMap;
use prime\objects\HeramsSubject;
use prime\objects\LanguageSet;
use prime\queries\ResponseForLimesurveyQuery;
use prime\validators\EnumValidator;
use prime\validators\ExistValidator;
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
 * @property array<string> $languages
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
 *
 */
class Project extends ActiveRecord implements Linkable, RequestableInterface
{
    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_PRIVATE = 'private';
    public const VISIBILITY_HIDDEN = 'hidden';
    public const STATUS_ONGOING = 0;
    public const STATUS_BASELINE = 1;
    public const STATUS_TARGET = 2;
    public const STATUS_EMERGENCY_SPECIFIC = 3;

    const PROGRESS_ABSOLUTE = 'absolute';
    const PROGRESS_PERCENTAGE = 'percentage';

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
                'virtualFields' => self::virtualFields()
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
        foreach (['overrides', 'typemap', 'title', 'contributorPermissionCount', 'responseCount'] as $hidden) {
            unset($fields[$hidden]);
        }
        return $fields;
    }

    public static function find(): ActiveQuery
    {
        return new ActiveQuery(get_called_class());
    }

    public function getFunctionalityCounts(): array
    {
        $query = $this->getResponses()
            ->groupBy([
                "json_unquote(json_extract([[data]], '$.{$this->getMap()->getFunctionality()}'))"
            ])
            ->select([
                'count' => 'count(*)',
                'functionality' => "json_unquote(json_extract([[data]], '$.{$this->getMap()->getFunctionality()}'))",
            ])
            ->indexBy('functionality')
            ->orderBy('functionality')
            ->asArray();

        $map = [
            'A1' => \Yii::t('app', 'Full'),
            'A2' => \Yii::t('app', 'Partial'),
            'A3' => \Yii::t('app', 'None')
        ];

        $result = [];
        foreach ($query->column() as $key => $value) {
            if (isset($map[$key])) {
                $label = $map[$key];
                $result[$label] = ($result[$label] ?? 0) + $value;
            }
        }
        return $result;
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
            'permission' => Permission::ROLE_LEAD
        ]);

        return User::find()->andWhere(['id' => $permissionQuery->select('source_id')])->all();
    }

    public function getLinks(): array
    {
        $result = [];
        $result[Link::REL_SELF] = Url::to(['project/view', 'id' => $this->id]);
        $result['summary'] = Url::to(['project/summary', 'id' => $this->id]);

        if (\Yii::$app->user->can(Permission::PERMISSION_READ, $this)) {
            if ($this->getOverride('dashboard')) {
                $result['dashboard'] = new Link([
                    'title' => \Yii::t('app', 'Dashboard'),
                    'type' => 'text/html',
                    'href' => Url::to(['/project/external-dashboard', 'id' => $this->id]),
                ]);
            } elseif ($this->getMainPages()->exists()) {
                $result['dashboard'] = new Link([
                    'title' => \Yii::t('app', 'Dashboard'),
                    'type' => 'text/html',
                    'href' => Url::to(['/project/view', 'id' => $this->id]),
                ]);
            }
        }

        $result['workspaces'] = new Link([
            'title' => \Yii::t('app', 'Workspaces'),
            'type' => 'text/html',
            'href' => Url::to(['/project/workspaces', 'id' => $this->id])
        ]);
        return $result;
    }

    public function getMainPages(): ActiveQuery
    {
        return $this->getPages()->andWhere(['parent_id' => null])->orderBy('sort');
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
        return $this->hasMany(Page::class, ['project_id' => 'id'])
            ->inverseOf('project')
            // First sort by parent_id ?? id to "group" by page id
            // Secondly order by parent_id to make sure the actual parent is first
            // Last order by the sorting column
            ->orderBy(['COALESCE([[parent_id]], [[id]])' => SORT_ASC, 'parent_id' => SORT_ASC, 'sort' => SORT_ASC]);
    }

    public function getPermissions(): ActiveQuery
    {
        return $this->hasMany(Permission::class, ['target_id' => 'id'])
            ->andWhere(['target' => self::class]);
    }

    public function getResponses(): ResponseForLimesurveyQuery
    {
        return $this->hasMany(ResponseForLimesurvey::class, ['workspace_id' => 'id'])->via('workspaces');
    }

    public function getStatusText(): string
    {
        return ProjectStatus::from($this->status)->label;
    }

    public function getSubjectAvailabilityCounts(): array
    {
        \Yii::beginProfile(__FUNCTION__);
        $counts = [
            HeramsSubject::FULLY_AVAILABLE => 0,
            HeramsSubject::PARTIALLY_AVAILABLE => 0,
            HeramsSubject::NOT_AVAILABLE => 0,
            HeramsSubject::NOT_PROVIDED => 0,
        ];
        /** @var HeramsResponseInterface $heramsResponse */
        foreach ($this->getResponses()->each() as $heramsResponse) {
            foreach ($heramsResponse->getSubjects() as $subject) {
                $subjectAvailability = $subject->getAvailability();
                if (!isset($subjectAvailability, $counts[$subjectAvailability])) {
                    continue;
                }
                $counts[$subjectAvailability]++;
            }
        }
        ksort($counts);
        $map = [
            'A1' => \Yii::t('app', 'Full'),
            'A2' => \Yii::t('app', 'Partial'),
            'A3' => \Yii::t('app', 'None'),
//            'A4' => \Yii::t('app', 'Not normally provided'),
        ];

        $result = [];
        foreach ($counts as $key => $value) {
            if (isset($map[$key])) {
                $result[$map[$key]] = $value;
            }
        }

        \Yii::endProfile(__FUNCTION__);
        return $result;
    }

    public function getType(): ProjectType
    {
        if (isset($this->base_survey_eid)) {
            return ProjectType::limesurvey();
        } else {
            return ProjectType::surveyJs();
        }
    }

    public function getTypeCounts(): array
    {
        if (null !== $result = $this->getOverride('typeCounts')) {
            return $result;
        }
        \Yii::beginProfile(__FUNCTION__);
        $map = is_array($this->typemap) ? $this->typemap : [];
        // Initialize counts
        $counts = [];
        foreach ($map as $key => $value) {
            $counts[$value] = 0;
        }

        $query = $this->getResponses()
            ->groupBy([
                "json_unquote(json_extract([[data]], '$.{$this->getMap()->getType()}'))"
            ])
            ->select([
                'count' => 'count(*)',
                'type' => "json_unquote(json_extract([[data]], '$.{$this->getMap()->getType()}'))",
            ])
            ->indexBy('type')
            ->asArray();

        foreach ($query->column() as $type => $count) {
            if (empty($map)) {
                $counts[$type] = ($counts[$type] ?? 0) + $count;
            } elseif (isset($map[$type])) {
                $counts[$map[$type]] += $count;
            }
        }

        \Yii::endProfile(__FUNCTION__);
        return $counts;
    }

    public function getWorkspaces(): ActiveQuery
    {
        return $this->hasMany(Workspace::class, ['project_id' => 'id'])->inverseOf('project');
    }

    public function init(): void
    {
        parent::init();
        $this->typemap = [
            'A1' => 'Primary',
            'A2' => 'Primary',
            'A3' => 'Secondary',
            'A4' => 'Secondary',
            'A5' => 'Tertiary',
            'A6' => 'Tertiary',
            "" => 'Other',
        ];

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
            [['base_survey_eid'], NumberValidator::class, 'integerOnly' => true],
            [['hidden'], BooleanValidator::class],
            [['latitude', 'longitude'], NumberValidator::class, 'integerOnly' => false],
            [['languages'], RangeValidator::class, 'allowArray' => true, 'range' => Language::toValues()],
            [['typemap', 'overrides', 'i18n'], function ($attribute) {
                if (!is_array($this->$attribute)) {
                    $this->addError($attribute, \Yii::t('app', '{attribute} must be an array.', ['attribute' => $this->getAttributeLabel($attribute)]));
                }
            }],
            [['status'], EnumValidator::class, 'enumClass' => ProjectStatus::class],
            [['visibility'], EnumValidator::class, 'enumClass' => ProjectVisibility::class],
            [['country'], function () {
                $data = new ISO3166();
                try {
                    $data->alpha3($this->country);
                } catch (\Throwable $t) {
                    $this->addError('country', $t->getMessage());
                }
            }],
            [['country'], DefaultValueValidator::class, 'value' => null],
            [['manage_implies_create_hf'], BooleanValidator::class],
            [['admin_survey_id', 'data_survey_id'], ExistValidator::class, 'targetClass' => Survey::class, 'targetAttribute' => 'id'],
            [['data_survey_id', 'admin_survey_id', 'base_survey_eid'], function (string $attribute, null|array $params, InlineValidator $validator) {
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
            }, 'skipOnEmpty' => false],
        ];
    }

    private static function virtualFields(): array
    {
        return [
            'latestDate' => [
                VirtualFieldBehavior::GREEDY => ResponseForLimesurvey::find()->limit(1)->select('max(date)')
                    ->where(['workspace_id' => Workspace::find()->select('id')->andWhere([
                        'project_id' => new Expression(self::tableName() . '.[[id]]')])
                    ]),
                VirtualFieldBehavior::LAZY => static fn(self $model): ?string
                    => $model->getResponses()->select('max([[date]])')->scalar()
            ],
            'workspaceCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::GREEDY => $workspaceCountGreedy = Workspace::find()->limit(1)->select('count(*)')
                    ->where(['project_id' => new Expression(self::tableName() . '.[[id]]')]),
                VirtualFieldBehavior::LAZY => static fn(self $model): int
                    => (int) $model->getWorkspaces()->count()

            ],
            'pageCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::GREEDY => Page::find()->limit(1)->select('count(*)')
                    ->where(['project_id' => new Expression(self::tableName() . '.[[id]]')]),
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    return (int) $model->getMainPages()->count();
                }
            ],
            'facilityCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::GREEDY => ResponseForLimesurvey::find()->andWhere([
                    'workspace_id' => Workspace::find()->select('id')
                        ->where(['project_id' => new Expression(self::tableName() . '.[[id]]')]),
                ])->addParams([':path' => '$.facilityCount'])->
                select(new Expression('coalesce(cast(json_unquote(json_extract([[overrides]], :path)) as unsigned), count(distinct [[workspace_id]], [[hf_id]]))')),
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    $override = $model->getOverride('facilityCount');
                    if (isset($override)) {
                        return (int)$override;
                    }
                    return $model->workspaceCount === 0 ? 0 : (int) $model->getResponses()->count(new Expression('DISTINCT [[hf_id]]'));
                }
            ],
            'responseCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::GREEDY => ResponseForLimesurvey::find()->andWhere([
                    'workspace_id' => Workspace::find()->select('id')
                        ->where(['project_id' => new Expression(self::tableName() . '.[[id]]')]),
                ])->addParams([':path' => '$.responseCount'])->
                select(new Expression('coalesce(cast(json_unquote(json_extract([[overrides]], :path)) as unsigned), count(*))'))
                ,
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    if ($model->workspaceCount === 0) {
                        return 0;
                    }
                    return (int)($model->getOverride('responseCount') ?? $model->getResponses()->count());
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
            'contributorPermissionCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::GREEDY => $contributorPermissionCountGreedy = Permission::find()->where([
                    'target' => Workspace::class,
                    'target_id' => Workspace::find()->select('id')
                        ->where(['project_id' => new Expression(self::tableName() . '.[[id]]')]),
                    'source' => User::class,
                ])->select('count(distinct [[source_id]])')
                ,
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    return (int) Permission::find()->where([
                        'target' => Workspace::class,
                        'target_id' => $model->getWorkspaces()->select('id'),
                        'source' => User::class,
                    ])->count('distinct [[source_id]]');
                }
            ],
            'contributorCount' => [
                VirtualFieldBehavior::GREEDY => (function () use ($contributorPermissionCountGreedy, $workspaceCountGreedy): ExpressionInterface {
                    $result = new Query();
                    $permissionCount = self::getDb()->queryBuilder->buildExpression($contributorPermissionCountGreedy, $result->params);
                    $workspaceCount = self::getDb()->queryBuilder->buildExpression($workspaceCountGreedy, $result->params);

                    $result->addParams([':ccpath' => '$.contributorCount']);
                    $result->select(new Expression("coalesce(cast(json_unquote(json_extract([[overrides]], :ccpath)) as unsigned), greatest($permissionCount, $workspaceCount))"));
                    return $result;
                })(),
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    return $model->getOverride('contributorCount') ?? max($model->contributorPermissionCount, $model->workspaceCount);
                }
            ]
        ];
    }

    public function getTitle(): string
    {
        return $this->getAttribute('title');
    }

    public function getRoute(): array
    {
        return ['project/update', 'id' => $this->id];
    }

    public function getProjectTitle(): string
    {
        return $this->getTitle();
    }

    public static function tableName()
    {
        return '{{%project}}';
    }
}
