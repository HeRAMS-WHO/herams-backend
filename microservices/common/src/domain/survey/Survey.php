<?php

declare(strict_types=1);

namespace herams\common\domain\survey;

use herams\common\behaviors\AuditableBehavior;
use herams\common\models\ActiveRecord;
use herams\common\models\SurveyResponse;
use herams\common\queries\ActiveQuery;
use prime\helpers\ArrayHelper;
use yii\validators\RequiredValidator;

/**
 * Attributes
 * @property array $config
 * @property string|null $created_date
 * @property int $id
 * @property string|null $last_modified_date
 *
 * Virtual fields
 * @property-read string $title
 */
final class Survey extends ActiveRecord
{
    public function getSurveyResponses(): ActiveQuery
    {
        return $this->hasMany(SurveyResponse::class, [
            'survey_id' => 'id',
        ]);
    }

    public function getTitle()
    {
        // return $this->config['title'] ?? \Yii::t('app', "Survey without title, id {id}", [
        //     'id' => $this->id,
        // ]);

        return ((isset($this->config['title']) ) ? ((is_array($this->config['title']) ) ? $this->config['title']['default'] : $this->config['title'] ): \Yii::t('app', "Survey without title, id {id}", [
            'id' => $this->id,
        ]));
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'config' => \Yii::t('app', 'Survey configuration'),
            'title' => \Yii::t('app', 'Title'),
        ]);
    }

    public function rules(): array
    {
        return [
            [['config'], RequiredValidator::class],
        ];
    }
}
