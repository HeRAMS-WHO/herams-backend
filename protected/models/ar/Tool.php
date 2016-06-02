<?php

namespace prime\models\ar;

use app\queries\ToolQuery;
use Befound\ActiveRecord\Behaviors\JsonBehavior;
use Befound\Components\Map;
use Befound\Components\UploadedFile;
use prime\interfaces\ProjectInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\models\ActiveRecord;
use prime\factories\GeneratorFactory;
use prime\models\permissions\Permission;
use prime\objects\ResponseCollection;
use prime\objects\SurveyCollection;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\helpers\ArrayHelper;
use yii\validators\BooleanValidator;
use yii\validators\RangeValidator;
use yii\validators\SafeValidator;

/**
 * Class Tool
 * @package prime\models
 *
 * @property string $imageUrl
 * @property int $base_survey_eid
 * @property int $intake_survey_eid
 * @property string $acronym
 * @property string $description
 * @property string $default_generator
 * @property Map $generators
 * @method static ToolQuery find()
 */
class Tool extends ActiveRecord implements ProjectInterface {

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
            'tempImage' => \Yii::t('app', 'Image'),
            'thumbTempImage' => \Yii::t('app', 'Thumbnail'),
            'intake_survey_eid' => \Yii::t('app', 'Intake survey'),
            'base_survey_eid' => \Yii::t('app', 'Base data survey'),
            'progress_type' => \Yii::t('app', "Project dashboard report"),
            'generators' => \Yii::t('app', "Reports"),
            'default_generator' => \Yii::t('app', "Default report"),
            'generatorsArray' => \Yii::t('app', "Reports"),
        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                JsonBehavior::class => [
                    'class' => JsonBehavior::class,
                    'jsonAttributes' => ['generators']
                ]
            ]
        );
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
//        vdd($this->base_survey_eid);
        return $this->limeSurvey()->getSurvey($this->base_survey_eid);
    }

    public function dataSurveyOptions()
    {
        $result = array_filter(ArrayHelper::map(app()->limeSurvey->listSurveys(), 'sid', function ($details) {
            if (substr_compare('[INTAKE]', $details['surveyls_title'], 0, 8) != 0
                && strpos($details['surveyls_title'], '_') === false
            ) {
                return $details['surveyls_title'] . (($details['active'] == 'N') ? " (INACTIVE)" : "");
            }

            return false;
        }));

        return $result;
    }

    public function beforeDelete()
    {
        return $this->getProjectCount() === 0;
    }

    public function getGeneratorsArray()
    {
        return $this->generators->asArray();
    }

    public function getImageUrl()
    {
        if (isset($this->image) && file_exists(\Yii::getAlias('@webroot') . self::IMAGE_PATH . $this->image)) {
            return self::IMAGE_PATH . $this->image;
        }
        return '/site/text-image?text=' . $this->acronym ;
    }

    public function getIntakeUrl()
    {
        return app()->limeSurvey->getUrl($this->intake_survey_eid);
    }

    public function getProjects()
    {
        return $this->hasMany(Project::class, ['tool_id' => 'id']);
    }
    public function getProjectCount()
    {
        return $this->getProjects()->count();
    }

    public function getThumbnailUrl() {
        if(isset($this->thumbnail) && file_exists(\Yii::getAlias('@webroot') . self::IMAGE_PATH . $this->thumbnail)) {
            return self::IMAGE_PATH . $this->thumbnail;
        } else {
            return $this->getImageUrl();
        }
    }

    public function intakeSurveyOptions()
    {
        return array_filter(ArrayHelper::map(app()->limeSurvey->listSurveys(), 'sid', function($details) {
            if (substr_compare('[INTAKE]', $details['surveyls_title'], 0, 8) === 0) {
                return trim(substr($details['surveyls_title'], 8)) . (($details['active'] == 'N') ? " (INACTIVE)" : "");
            }
            return false;
        }));
    }

    public function isTransactional($operation)
    {
        return true;
    }

    public function rules()
    {
        return [
            [['title', 'acronym', 'description', 'intake_survey_eid', 'base_survey_eid', 'progress_type', 'generators', 'generatorsArray'], 'required'],
            //[['tempImage'], 'required', 'on' => ['create']],
            [['title', 'acronym', 'description'], 'string'],
            [['title'], 'unique'],
            [['tempImage', 'thumbTempImage'], 'image'],
            [['intake_survey_eid', 'base_survey_eid'], 'integer'],
            [['progress_type'], RangeValidator::class, 'range' => array_keys(GeneratorFactory::classes())],
            [['hidden'], BooleanValidator::class],
            [['generatorsArray'], RangeValidator::class, 'range' => array_keys(GeneratorFactory::classes()), 'allowArray' => true],
            [
            ['default_generator'],
                RangeValidator::class,
                'range' => function(self $model, $attribute) { return array_keys($model->generatorOptions()); },
                'enableClientValidation'=> false
            ]
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['title', 'acronym', 'description', 'tempImage', 'intake_survey_eid', 'base_survey_eid', 'progress_type', 'thumbTempImage', 'generatorsArray', 'hidden'],
            'update' => ['title', 'acronym', 'description', 'tempImage', 'thumbTempImage', 'generatorsArray', 'base_survey_eid', 'progress_type', 'hidden', 'default_generator']
        ];
    }

    /**
     * Saves an image for this record using the id
     * @param UploadedFile $image
     * @param string $postfix
     * @return null|string
     * @throws \Exception
     */
    public function saveImage(UploadedFile $image, $postfix = '')
    {
        if($this->isNewRecord) {
            throw new \Exception('Cannot save an image for a unsaved tool');
        }

        $fileNameWithExtension = $this->id . $postfix . '.' . $image->extension;
        $filePath = \Yii::getAlias('@webroot') . self::IMAGE_PATH;

        if(!is_writable($filePath)) {
            throw new \Exception('Unwritable image path');
        }

        if($image->saveAs($filePath . $fileNameWithExtension)) {
            return $fileNameWithExtension;
        } else {
            return null;
        }
    }

    public function setGeneratorsArray($value)
    {
        $this->generators = new Map($value);
    }

    public function generatorOptions()
    {
        return GeneratorFactory::options();
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
                app()->cache->set($key, $this->_responses, 60);
            }

        }
        return $this->_responses;
    }

    public function getSurveys()
    {
        $result = new SurveyCollection();
        $surveyIds = [];
        /** @var ResponseInterface $response */
        foreach($this->getResponses() as $response) {
            $surveyIds[$response->getSurveyId()] = true;
        }
        foreach($surveyIds as $surveyId => $dummy) {
            $result->append($this->limeSurvey()->getSurvey($surveyId));
        }
        return $result;
    }


    /**
     * Returns the name of the location of the project
     * @return string
     */
    public function getLocality()
    {
        return 'Tool';
    }


    public function getReports() {
        return $this->hasMany(Report::class, ['id' => 'tool_id'])->via('projects');
    }
    /**
     * Return the url to the tool image
     * @return string
     */
    public function getToolImagePath()
    {
        return $this->imageUrl;
    }

    public function getProgressReport()
    {
        if (isset($this->progress_type)) {
            /** @var ReportGeneratorInterface $generator */
            $generator = GeneratorFactory::get($this->progress_type);
            $generator = GeneratorFactory::get('progress');
            return $generator->render($this->getResponses(), $this->getSurveys(), $this,
                app()->user->identity->createSignature());
        }
    }

    public function userCan($operation, User $user)
    {
        return Permission::isAllowed($user, $this, Permission::PERMISSION_INSTANTIATE);
    }
}