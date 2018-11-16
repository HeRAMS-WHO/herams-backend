<?php

namespace prime\models\ar;

use app\queries\ToolQuery;
use prime\factories\GeneratorFactory;
use prime\interfaces\ResponseCollectionInterface;
use prime\models\ActiveRecord;
use prime\models\permissions\Permission;
use prime\objects\ResponseCollection;
use prime\objects\SurveyCollection;
use prime\tests\_helpers\Survey;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use yii\helpers\ArrayHelper;
use yii\validators\BooleanValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use yii\web\UploadedFile;

/**
 * Class Tool
 * @property int $id
 * @property int $base_survey_eid
 * @property string $title
 * @method static ToolQuery find()
 */
class Tool extends ActiveRecord {

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
    public function getBaseSurvey()
    {
        return new Survey();
        try {
            return $this->limeSurvey()->getSurvey($this->base_survey_eid);
        } catch (\Throwable $t) {
            return null;
        }

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
        return $this->hasMany(Project::class, ['tool_id' => 'id']);
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
            [['title'], UniqueValidator::class],
            [['base_survey_eid'], RangeValidator::class, 'range' => array_keys($this->dataSurveyOptions())],
            [['hidden'], BooleanValidator::class],
        ];
    }

    // Cache for getResponses();
    private $_responses;

    /**
     * @return ResponseCollectionInterface
     */
    public function getResponses()
    {
        if (!isset($this->_responses)) {
            $key = "{$this->id}-responses";
            if (false === $this->_responses = app()->cache->get($key)) {
                $this->_responses = new ResponseCollection();
                /** @var Project $project */
                foreach ($this->projects as $project) {
                    foreach ($project->getResponses() as $response) {
                        $this->_responses->append($response);
                    }
                }
                //app()->cache->set($key, $this->_responses, 60);
            }

        }
        return $this->_responses;
    }

    public function tokenOptions(): array
    {

        return [
            'abc' => 'token abc'
        ];
        $limeSurvey = $this->limeSurvey();
        $usedTokens = array_flip(Project::find()->select('token')->column());
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

}
