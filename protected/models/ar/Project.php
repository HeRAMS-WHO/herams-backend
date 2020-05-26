<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\LimesurveyDataProvider;
use prime\components\Link;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ActiveRecord;
use prime\models\permissions\Permission;
use prime\objects\HeramsCodeMap;
use prime\objects\HeramsSubject;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use SamIT\Yii2\VirtualFields\VirtualFieldQueryBehavior;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\validators\BooleanValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use yii\web\Linkable;
use function iter\filter;

/**
 * Class Tool
 * @property int $id
 * @property int $base_survey_eid
 * @property string $title
 * @property string $visibility
 * @property Page[] $pages
 * @property int $status
 * @property Workspace[] $workspaces
 * @property-read int $workspaceCount
 * @property-read int $contributorCount
 * @property-read string $latestDate
 * @property-read int $facilityCount
 * @property-read int $contributorPermissionCount
 * @property-read SurveyInterface $survey
 * @property array $overrides
 */
class Project extends ActiveRecord implements Linkable
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

    public function getStatusText(): string
    {
        return $this->statusOptions()[$this->status];
    }

    public function isHidden(): bool
    {
        return $this->visibility === self::VISIBILITY_HIDDEN;
    }

    public function visibilityOptions()
    {
        return [
            self::VISIBILITY_HIDDEN => 'Hidden, this project is only visible to people with permissions',
            self::VISIBILITY_PUBLIC => 'Public, anyone can view this project',
            self::VISIBILITY_PRIVATE => 'Private, this project is visible on the map and in the list, but people need permission to view it'
        ];
    }


    public static function find()
    {
        $result = new ActiveQuery(self::class);
        $result->attachBehaviors([
            VirtualFieldQueryBehavior::class => [
                'class' => VirtualFieldQueryBehavior::class
            ]
        ]);
        return $result;
    }

    public function beforeSave($insert)
    {
        $this->overrides = array_filter($this->overrides);
        return parent::beforeSave($insert);
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

    public function extraFields(): array
    {
        $result = parent::extraFields();
        $result['subjectAvailabilityCounts'] = 'subjectAvailabilityCounts';
        $result['functionalityCounts'] = 'functionalityCounts';
        $result['typeCounts'] = 'typeCounts';
        $result['statusText'] = 'statusText';

        return $result;
    }

    public function init()
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

    public function statusOptions()
    {
        return [
            self::STATUS_ONGOING => 'Ongoing',
            self::STATUS_BASELINE => 'Baseline',
            self::STATUS_TARGET => 'Target',
            self::STATUS_EMERGENCY_SPECIFIC => 'Emergency specific'
        ];
    }

    public function attributeLabels()
    {
        return [
            'base_survey_eid' => \Yii::t('app', 'Survey'),
            'title' => \Yii::t('app', 'Title'),
        ];
    }

    public function attributeHints()
    {
        return [
            'name_code' => \Yii::t('app', 'Question code containing the name (case sensitive)'),
            'type_code' => \Yii::t('app', 'Question code containing the type (case sensitive)'),
            'typemap' => \Yii::t('app', 'Map facility types for use in the world map'),
            'status' => \Yii::t('app', 'Project status is shown on the world map')
        ];
    }

    /**
     * @return LimesurveyDataProvider
     */
    protected function limesurveyDataProvider()
    {
        return app()->limesurveyDataProvider;
    }

    /**
     * @return \SamIT\LimeSurvey\Interfaces\SurveyInterface
     */
    public function getSurvey(): SurveyInterface
    {
        return $this->limesurveyDataProvider()->getSurvey($this->base_survey_eid);
    }

    public function dataSurveyOptions()
    {
        $existing = Project::find()->select('base_survey_eid')->indexBy('base_survey_eid')->column();

        $surveys = filter(function ($details) use ($existing) {
            return $this->base_survey_eid == $details['sid'] || !isset($existing[$details['sid']]);
        }, $this->limesurveyDataProvider()->listSurveys());

        $result = ArrayHelper::map($surveys, 'sid', function ($details) use ($existing) {
                return $details['surveyls_title'] . (($details['active'] == 'N') ? " (INACTIVE)" : "");
        });

        return $result;
    }

    public function getWorkspaces()
    {
        return $this->hasMany(Workspace::class, ['tool_id' => 'id'])->inverseOf('project');
    }

    public function getTypemapAsJson()
    {
        return Json::encode($this->typemap, JSON_PRETTY_PRINT);
    }

    public function getOverridesAsJson()
    {
        return Json::encode($this->overrides, JSON_PRETTY_PRINT);
    }

    public function setTypemapAsJson($value)
    {
        $this->typemap = Json::decode($value);
    }

    public function setOverridesAsJson(string $value)
    {
        $this->overrides = array_filter(Json::decode($value));
    }


    public function rules()
    {
        return [
            [[
                'title', 'base_survey_eid'
            ], RequiredValidator::class],
            [['title'], StringValidator::class],
            [['title'], UniqueValidator::class],
            [['base_survey_eid'], RangeValidator::class, 'range' => array_keys($this->dataSurveyOptions())],
            [['hidden'], BooleanValidator::class],
            [['latitude', 'longitude'], NumberValidator::class, 'integerOnly' => false],
            [['typemapAsJson', 'overridesAsJson'], SafeValidator::class],
            [['status'], RangeValidator::class, 'range' => array_keys($this->statusOptions())],
            [['visibility'], RangeValidator::class, 'range' => array_keys($this->visibilityOptions())]
        ];
    }

    public function behaviors()
    {
        return [
            'virtualFields' => [
                'class' => VirtualFieldBehavior::class,
                'virtualFields' => [
                    'latestDate' => [
                        VirtualFieldBehavior::GREEDY => Response::find()->limit(1)->select('max(date)')
                            ->where(['workspace_id' => Workspace::find()->select('id')->andWhere([
                                'tool_id' => new Expression(self::tableName() . '.[[id]]')])
                            ]),
                        VirtualFieldBehavior::LAZY => static function (self $model): ?string {
                            return $model->getResponses()->select('max([[date]])')->scalar();
                        }
                    ],
                    'workspaceCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Workspace::find()->limit(1)->select('count(*)')
                            ->where(['tool_id' => new Expression(self::tableName() . '.[[id]]')]),
                        VirtualFieldBehavior::LAZY => static function (self $model): int {
                            return (int) $model->getWorkspaces()->count();
                        }
                    ],
                    'facilityCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Response::find()->andWhere([
                                'workspace_id' => Workspace::find()->select('id')
                                    ->where(['tool_id' => new Expression(self::tableName() . '.[[id]]')]),
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
                        VirtualFieldBehavior::GREEDY => Response::find()->andWhere([
                            'workspace_id' => Workspace::find()->select('id')
                                ->where(['tool_id' => new Expression(self::tableName() . '.[[id]]')]),
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
                    'contributorPermissionCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Permission::find()->where([
                            'target' => Workspace::class,
                            'target_id' => Workspace::find()->select('id')
                                ->where(['tool_id' => new Expression(self::tableName() . '.[[id]]')]),
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
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::LAZY => static function (self $model): int {
                            return $model->getOverride('contributorCount') ?? max($model->contributorPermissionCount, $model->workspaceCount);
                        }
                    ]
                ]
            ]
        ];
    }

    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['workspace_id' => 'id'])->via('workspaces');
    }

    public function getTypeCounts()
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


    public function getSubjectAvailabilityCounts(): array
    {
        \Yii::beginProfile(__FUNCTION__);
        $counts = [
            HeramsSubject::FULLY_AVAILABLE => 0,
            HeramsSubject::PARTIALLY_AVAILABLE => 0,
            HeramsSubject::NOT_AVAILABLE => 0,
            HeramsSubject::NOT_PROVIDED=> 0,
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

    public function getPermissions()
    {
        return $this->hasMany(Permission::class, ['target_id' => 'id'])
            ->andWhere(['target' => self::class]);
    }

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        throw new NotSupportedException();
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        throw new NotSupportedException();
    }

    public function getMap(): HeramsCodeMap
    {
        return new HeramsCodeMap();
    }

    public function getPages()
    {
        return $this->hasMany(Page::class, ['project_id' => 'id'])->andWhere(['parent_id' => null])->orderBy('sort');
    }

    public function getAllPages()
    {
        return $this->hasMany(Page::class, ['project_id' => 'id'])->orderBy('COALESCE([[parent_id]], [[id]])');
    }



    /**
     * @param $name
     * @return mixed|null
     */
    public function getOverride($name)
    {
        return $this->overrides[$name] ?? null;
    }

    public function exportDashboard(): array
    {
        $pages = [];
        foreach ($this->pages as $page) {
            $pages[] = $page->export();
        }
        return $pages;
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
            } elseif ($this->getPages()->exists()) {
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
}
