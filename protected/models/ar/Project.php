<?php

namespace prime\models\ar;

use app\queries\ToolQuery;
use prime\components\JsonValidator;
use prime\factories\GeneratorFactory;
use prime\interfaces\FacilityListInterface;
use prime\interfaces\ProjectInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\WorkspaceListInterface;
use prime\lists\SurveyFacilityList;
use prime\lists\WorkspaceList;
use prime\models\ActiveRecord;
use prime\models\forms\ResponseFilter;
use prime\models\permissions\Permission;
use prime\objects\FacilityType;
use prime\objects\HeramsCodeMap;
use prime\objects\HeramsResponse;
use prime\objects\ResponseCollection;
use prime\objects\SurveyCollection;
use prime\tests\_helpers\Survey;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\validators\BooleanValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use yii\web\Link;
use yii\web\Linkable;
use yii\web\UploadedFile;

/**
 * Class Tool
 * @property int $id
 * @property int $base_survey_eid
 * @property string $title
 * @method static ToolQuery find()
 * @property Page[] $pages
 * @property int $status
 */
class Project extends ActiveRecord implements ProjectInterface, Linkable {

    public const STATUS_ONGOING = 0;
    public const STATUS_BASELINE = 1;
    public const STATUS_TARGET = 2;
    public const STATUS_EMERGENCY_SPECIFIC = 3;

    const PROGRESS_ABSOLUTE = 'absolute';
    const PROGRESS_PERCENTAGE = 'percentage';

    public function statusText(): string
    {
        return $this->statusOptions()[$this->status];
    }
    public function init()
    {
        parent::init();
        $this->typemap = Json::encode([
            'A1' => 'Primary',
            'A2' => 'Primary',
            'A3' => 'Secondary',
            'A4' => 'Secondary',
            'A5' => 'Tertiary',
            'A6' => 'Tertiary',
            "" => 'Other',
        ]);
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
            'base_survey_eid' => \Yii::t('app', 'Survey')
        ];
    }

    public function attributeHints()
    {
        return [
            'latitude_code' => \Yii::t('app', 'Question code containing the latitude (case sensitive)'),
            'longitude_code' => \Yii::t('app', 'Question code containing the longitude (case sensitive)'),
            'name_code' => \Yii::t('app', 'Question code containing the name (case sensitive)'),
            'type_code' => \Yii::t('app', 'Question code containing the type (case sensitive)'),
            'typemap' => \Yii::t('app', 'Map facility types for use in the world map'),
            'status' => \Yii::t('app','Project status is shown on the world map')
        ];
    }

    /**
     * @return \SamIT\LimeSurvey\JsonRpc\Client
     */
    protected function limeSurvey() {
        return app()->limeSurvey;
    }

    /**
     * @return \SamIT\LimeSurvey\Interfaces\SurveyInterface
     */
    public function getSurvey(): SurveyInterface
    {
        return $this->limeSurvey()->getSurvey($this->base_survey_eid);
    }

    public function dataSurveyOptions()
    {
        $result = ArrayHelper::map(app()->limeSurvey->listSurveys(), 'sid', function ($details) {
            return $details['surveyls_title'] . (($details['active'] == 'N') ? " (INACTIVE)" : "");
        });

        return $result;
    }

    public function beforeDelete()
    {
        return $this->getWorkspaceCount() === 0;
    }

    public function getProjects()
    {
        return $this->hasMany(Workspace::class, ['tool_id' => 'id']);
    }
    public function getWorkspaceCount()
    {
        return $this->getProjects()->count();
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
            [['typemap'], JsonValidator::class, 'rootType' => JsonValidator::ROOT_OBJECT],
            [['status'], RangeValidator::class, 'range' => array_keys($this->statusOptions())]
        ];
    }

    // Cache for getResponses();
    private $_responses;

    /**
     * @return ResponseInterface[]
     */
    public function getResponses()
    {
        if (!isset($this->_responses)) {
            $this->_responses = $this->limeSurvey()->getResponses($this->base_survey_eid);
        }
        return $this->_responses;
    }


    /**
     * @return iterable|HeramsResponse[]
     */
    public function getHeramsResponses(): iterable
    {

        $map = $this->getMap();
        $heramsResponses = [];
        foreach($this->getResponses() as $response) {
            try {
                $heramsResponses[] = new HeramsResponse($response, $map);
            } catch (\InvalidArgumentException $e) {
                // Silent ignore invalid responses.
            }
        }
        return (new ResponseFilter($heramsResponses, $this->getSurvey()))->filter();
    }

    public function getTypeCounts()
    {
        $map = Json::decode($this->typemap);
        // Always have a mapping for the empty / unknown value.
        if (!isset($map[""])) {
            $map[""] = "Unknown";
        }
        // Initialize counts
        $counts = [];
        foreach($map as $key => $value) {
            $counts[$value] = 0;
        }

        foreach($this->getHeramsResponses() as $response) {
            $type = $response->getType();
            if (empty($map)) {
                $counts[$type] = ($counts[$type] ?? 0) + 1;
            } elseif (isset($map[$type])) {
                $counts[$map[$type]]++;
            } else {
                $counts[$map[""]]++;
            }
        }
        return $counts;
    }

    public function getFunctionalityCounts()
    {
        return [
            'Fully' => mt_rand(1, 100),
            'Partially' => mt_rand(1, 100),
            'None' => mt_rand(1, 100),
        ];
    }

    public function userCan($operation, User $user)
    {
        return $user->isAdmin || Permission::isAllowed($user, $this, Permission::PERMISSION_INSTANTIATE);
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

    public function getId(): string
    {
        return $this->getAttribute('id');
    }

    public function getWorkspaces(): WorkspaceListInterface
    {
        return new WorkspaceList($this->projects);
    }

    /**
     * Returns a list of links.
     *
     * Each link is either a URI or a [[Link]] object. The return value of this method should
     * be an array whose keys are the relation names and values the corresponding links.
     *
     * If a relation name corresponds to multiple links, use an array to represent them.
     *
     * For example,
     *
     * ```php
     * [
     *     'self' => 'http://example.com/users/1',
     *     'friends' => [
     *         'http://example.com/users/2',
     *         'http://example.com/users/3',
     *     ],
     *     'manager' => $managerLink, // $managerLink is a Link object
     * ]
     * ```
     *
     * @return array the links
     */
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['project/view', 'id' => $this->id], true),
            'workspaces' => Url::to(['workspace/index', 'filter' => ['tool_id' => $this->id]], true),
        ];
    }

    public function getFacilities(): FacilityListInterface
    {
        return new SurveyFacilityList($this->getSurvey());
    }

    public function getMap(): HeramsCodeMap
    {
        return new HeramsCodeMap();
    }

    public function getPages() {
        return $this->hasMany(Page::class, ['tool_id' => 'id'])->andWhere(['parent_id' => null])->orderBy('sort');
    }

    public function getAllPages()
    {
        return $this->hasMany(Page::class, ['tool_id' => 'id'])->orderBy('COALESCE([[parent_id]], [[id]])');
    }

    public function getType(): FacilityType
    {
        return new FacilityType(FacilityType::PRIMARY);
    }

    public function getContributorCount(): int
    {
        return 1 + $this->getPermissions()->count();
    }

    public function getFacilityCount(): int
    {
        return count($this->getHeramsResponses());
    }
}
