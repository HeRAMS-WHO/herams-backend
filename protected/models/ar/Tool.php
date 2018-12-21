<?php

namespace prime\models\ar;

use app\queries\ToolQuery;
use prime\factories\GeneratorFactory;
use prime\interfaces\FacilityListInterface;
use prime\interfaces\ProjectInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\WorkspaceListInterface;
use prime\lists\SurveyFacilityList;
use prime\lists\WorkspaceList;
use prime\models\ActiveRecord;
use prime\models\permissions\Permission;
use prime\objects\ResponseCollection;
use prime\objects\SurveyCollection;
use prime\tests\_helpers\Survey;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\validators\BooleanValidator;
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
 */
class Tool extends ActiveRecord implements ProjectInterface, Linkable {

    const IMAGE_PATH = '/img/tools/';

    const PROGRESS_ABSOLUTE = 'absolute';
    const PROGRESS_PERCENTAGE = 'percentage';

    /**
     * variable for uploading tool image
     * @var UploadedFile
     */
    public $tempImage;
    public $thumbTempImage;


    /**
     * Save images after saving record to database.
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        /**
         * Save image and thumbnail if set
         * This has to be done in the after save because the id of the record is needed
         * Only save if one of both is set
         */
        $save = false;

        if(isset($this->tempImage)) {
            $this->image = $this->saveImage($this->tempImage);
            unset($this->tempImage);
            $save = true;
        }

        if(isset($this->thumbTempImage)) {
            $this->thumbnail = $this->saveImage($this->thumbTempImage, '_thumbnail');
            unset($this->thumbTempImage);
            $save = true;
        }

        if($save) {
            $this->save(false);
        }
    }

    public function attributeLabels()
    {
        return [
            'base_survey_eid' => \Yii::t('app', 'Base data survey')
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
    public function getBaseSurvey(): SurveyInterface
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
        return $this->getProjectCount() === 0;
    }

    public function getProjects()
    {
        return $this->hasMany(Workspace::class, ['tool_id' => 'id']);
    }
    public function getProjectCount()
    {
        return $this->getProjects()->count();
    }

    public function isTransactional($operation)
    {
        return true;
    }

    public function rules()
    {
        return [
            [[
                'title', 'base_survey_eid'
            ], RequiredValidator::class],
            [['title'], StringValidator::class],
//            [['title'], UniqueValidator::class],
            [['base_survey_eid'], RangeValidator::class, 'range' => array_keys($this->dataSurveyOptions())],
            [['hidden'], BooleanValidator::class],
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

    public function tokenOptions(): array
    {

        return [
            'abc' => 'token abc'
        ];
        $limeSurvey = $this->limeSurvey();
        $usedTokens = array_flip(Workspace::find()->select('token')->column());
        $tokens = $limeSurvey->getTokens($this->base_survey_eid);

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

        return $result;
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
        return new SurveyFacilityList($this->getBaseSurvey());
    }
}
