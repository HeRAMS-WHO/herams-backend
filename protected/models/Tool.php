<?php

namespace prime\models;

use Befound\ActiveRecord\Behaviors\JsonBehavior;
use Befound\Components\UploadedFile;
use prime\models\permissions\Permission;
use prime\widgets\progress\Percentage;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\validators\RangeValidator;

/**
 * Class Tool
 * @package prime\models
 *
 * @property string $imageUrl
 * @property Widget progressWidget
 */
class Tool extends \prime\components\ActiveRecord {

    const IMAGE_PATH = 'img/tools/';

    const PROGRESS_ABSOLUTE = 'absolute';
    const PROGRESS_PERCENTAGE = 'percentage';

    /**
     * variable for uploading tool image
     * @var UploadedFile
     */
    public $tempImage;
    public $thumbTempImage;

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


    public static function generatorOptions()
    {
        return [
            'test' => \prime\reportGenerators\test\Generator::class
        ];
    }

    public function getGenerators()
    {
        return self::generatorOptions();
    }

    public function getImageUrl()
    {
        return '/' . self::IMAGE_PATH . $this->image;
    }

    public static function getProgressOptions()
    {
        return [
            self::PROGRESS_PERCENTAGE => \Yii::t('app', 'Percentage')
        ];
    }

    /**
     * @return Widget
     */
    public function getProgressWidget()
    {
        switch($this->progress_type)
        {
            case self::PROGRESS_PERCENTAGE:
                return new Percentage();
        }
    }

    public function getThumbnailUrl() {
        if(isset($this->thumbnail)) {
            return '/' . self::IMAGE_PATH . $this->thumbnail;
        } else {
            return $this->getImageUrl();
        }
    }

    public function isTransactional($operation)
    {
        return true;
    }

    public function rules()
    {
        return [
            [['title', 'description', 'intake_survey_eid', 'base_survey_eid', 'progress_type', 'generators'], 'required'],
            [['tempImage'], 'required', 'on' => ['create']],
            [['title', 'description'], 'string'],
            [['title'], 'unique'],
            [['tempImage', 'thumbTempImage'], 'image'],
            [['intake_survey_eid', 'base_survey_eid'], 'integer'],
            [['progress_type'], 'string'],
            [['progress_type'], 'in', 'range' => array_keys(self::getProgressOptions())],
            [['generators'], RangeValidator::class, 'range' => array_keys(self::generatorOptions()), 'allowArray' => true]
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['title', 'description', 'tempImage', 'intake_survey_eid', 'base_survey_eid', 'progress_type', 'thumbTempImage', 'generators'],
            'update' => ['title', 'description', 'tempImage', 'thumbTempImage', 'generators']
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