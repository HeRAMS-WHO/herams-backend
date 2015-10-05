<?php

namespace prime\models;

use Befound\Components\UploadedFile;
use yii\helpers\FileHelper;

/**
 * Class Tool
 * @package prime\models
 *
 * @property string $imageUrl
 */
class Tool extends \prime\components\ActiveRecord {

    const IMAGE_PATH = 'img/tools/';

    /**
     * variable for uploading tool image
     * @var UploadedFile
     */
    public $tempImage;

    public function getImageUrl()
    {
        return '/' . self::IMAGE_PATH . $this->image;
    }

    public function rules()
    {
        return [
            [['title', 'description', 'intake_survey_eid', 'base_survey_eid', 'tempImage'], 'required'],
            [['title', 'description'], 'string'],
            [['title'], 'unique'],
            [['tempImage'], 'image'],
            [['intake_survey_eid', 'base_survey_eid'], 'integer']
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['title', 'description', 'tempImage', 'intake_survey_eid', 'base_survey_eid']
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