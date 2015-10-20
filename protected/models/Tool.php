<?php

namespace prime\models;

use Befound\Components\UploadedFile;
use prime\widgets\progress\Absolute;
use yii\base\Widget;
use yii\helpers\FileHelper;

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

    /**
     * variable for uploading tool image
     * @var UploadedFile
     */
    public $tempImage;

    public function getImageUrl()
    {
        return '/' . self::IMAGE_PATH . $this->image;
    }

    public static function getProgressOptions()
    {
        return [
            self::PROGRESS_ABSOLUTE => \Yii::t('app', 'Absolute')
        ];
    }

    /**
     * @return Widget
     */
    public function getProgressWidget()
    {
        switch($this->progress_type)
        {
            case self::PROGRESS_ABSOLUTE:
                return new Absolute();
        }
    }

    public function rules()
    {
        return [
            [['title', 'description', 'intake_survey_eid', 'base_survey_eid', 'progress_type'], 'required'],
            [['tempImage'], 'required', 'on' => ['create']],
            [['title', 'description'], 'string'],
            [['title'], 'unique'],
            [['tempImage'], 'image'],
            [['intake_survey_eid', 'base_survey_eid'], 'integer'],
            [['progress_type'], 'string'],
            [['progress_type'], 'in', 'range' => array_keys(self::getProgressOptions())]
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['title', 'description', 'tempImage', 'intake_survey_eid', 'base_survey_eid', 'progress_type'],
            'update' => ['title', 'description', 'tempImage']
        ];
    }

    /**
     * Saves the temporary image and sets the new filename
     * @return bool
     */
    public function saveTempImage()
    {
        $result = false;
        if (isset($this->tempImage) && $this->tempImage instanceof UploadedFile) {
            if($this->isNewRecord) {
                throw new \Exception('Cannot save an image for a unsaved tool');
            }

            $saveImageUrl = self::IMAGE_PATH . $this->id . '.' . $this->tempImage->extension;
            if($this->tempImage->saveAs($saveImageUrl)) {
                $this->image = $this->id . '.' . $this->tempImage->extension;
                $this->save(false);
                $result = true;
            }
        }
        return $result;
    }
}