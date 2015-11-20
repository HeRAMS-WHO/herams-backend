<?php

namespace prime\models\ar;

use Befound\ActiveRecord\Behaviors\JsonBehavior;
use Befound\Components\Map;
use Befound\Components\UploadedFile;
use prime\components\ActiveRecord;
use prime\factories\GeneratorFactory;
use prime\interfaces\ReportGeneratorInterface;
use prime\models\permissions\Permission;
use prime\models\ar\User;
use prime\widgets\progress\Percentage;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\validators\RangeValidator;
use yii\validators\SafeValidator;

/**
 * Class Tool
 * @package prime\models
 *
 * @property string $imageUrl
 * @property Widget progressWidget
 * @property int $base_survey_eid
 * @property int $intake_survey_eid
 * @property Map $generators
 */
class Tool extends ActiveRecord {

    const IMAGE_PATH = 'img/tools/';

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
            'progress_type' => \Yii::t('app', "Progress report")
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

    public function dataSurveyOptions()
    {
        return array_filter(ArrayHelper::map(app()->limesurvey->listSurveys(), 'sid', function($details) {
            if (substr_compare('[INTAKE]', $details['surveyls_title'], 0, 8) != 0
                && strpos($details['surveyls_title'], '_') === false
            ) {
                return $details['surveyls_title'] . (($details['active'] == 'N') ? " (INACTIVE)" : "");
            }
            return false;
        }));
    }

    public function getImageUrl()
    {
        return '/' . self::IMAGE_PATH . $this->image;
    }

    public function getIntakeUrl()
    {
        return app()->limesurvey->getUrl($this->intake_survey_eid);
    }

    public function getThumbnailUrl() {
        if(isset($this->thumbnail)) {
            return '/' . self::IMAGE_PATH . $this->thumbnail;
        } else {
            return $this->getImageUrl();
        }
    }

    public function intakeSurveyOptions()
    {
        return array_filter(ArrayHelper::map(app()->limesurvey->listSurveys(), 'sid', function($details) {
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
            [['title', 'acronym', 'description', 'intake_survey_eid', 'base_survey_eid', 'progress_type', 'generators'], 'required'],
            [['tempImage'], 'required', 'on' => ['create']],
            [['title', 'acronym', 'description'], 'string'],
            [['title'], 'unique'],
            [['tempImage', 'thumbTempImage'], 'image'],
            [['intake_survey_eid', 'base_survey_eid'], 'integer'],
            [['progress_type'], 'string'],
//            [['progress_type'], RangeValidator::class, 'range' => array_keys(GeneratorFactory::classes())],
        // Validation disabled until this is merged: https://github.com/yiisoft/yii2/pull/10162
            [['generators'], SafeValidator::class],
//            [['generators'], RangeValidator::class, 'range' => array_keys(GeneratorFactory::classes()), 'allowArray' => true]
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['title', 'acronym', 'description', 'tempImage', 'intake_survey_eid', 'base_survey_eid', 'progress_type', 'thumbTempImage', 'generators'],
            'update' => ['title', 'acronym', 'description', 'tempImage', 'thumbTempImage', 'generators']
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
        $filePath = self::IMAGE_PATH;

        if(!is_writable($filePath)) {
            throw new \Exception('Unwritable image path');
        }

        if($image->saveAs($filePath . $fileNameWithExtension)) {
            return $fileNameWithExtension;
        } else {
            return null;
        }

    }

    public function userCan($operation, User $user = null)
    {
        return $operation == Permission::PERMISSION_READ ||
            parent::userCan($operation, $user);
    }

}