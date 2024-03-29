<?php

declare(strict_types=1);

namespace herams\api\models;

use Collecthor\DataInterfaces\RecordInterface;
use herams\common\attributes\Field;
use herams\common\values\DatetimeValue;
use herams\common\values\FacilityId;
use herams\common\values\SurveyId;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\validators\RequiredValidator;

class NewSurveyResponse extends Model
{
    #[Field('survey_id')]
    public SurveyId|null $surveyId = null;

    #[Field('facility_id')]
    public FacilityId|null $facilityId = null;

    public RecordInterface|null $data = null;

    public $status;

    public $date_of_update;

    public $response_type;

    #[Field('created_by')]
    public int|null $createdBy = null;

    #[Field('last_modified_by')]
    public int|null $lastModifiedBy = null;

    #[Field('created_date')]
    public DatetimeValue|null $createdDate = null;

    #[Field('last_modified_date')]
    public DatetimeValue|null $lastModifiedDate = null;

    public function behaviors()
    {
        return [
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_date',
                'updatedAtAttribute' => 'last_modified_date',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->createdDate = new \yii\db\Expression('NOW() + INTERVAL 1 HOUR');
        }
        $this->lastModifiedDate = new \yii\db\Expression('NOW() + INTERVAL 1 HOUR');

        return parent::beforeSave($insert);
    }

    public function rules(): array
    {
        return [
            [['surveyId', 'facilityId', 'data', 'createdDate', 'createdBy', 'lastModifiedDate', 'lastModifiedBy'], RequiredValidator::class],
        ];
    }
}
