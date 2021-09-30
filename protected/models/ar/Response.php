<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ActiveRecord;
use prime\queries\ResponseQuery;

/**
 * Attributes
 * @property int $auto_increment_id
 * @property array $data
 * @property string|\DateTimeInterface $date The date of the information
 * @property string|\DateTimeInterface $last_updated The last time this response was synced
 * @property int $facility_id
 * @property int $id
 * @property int $survey_id
 * @property int $workspace_id
 *
 * Virtual attributes
 * @property Facility $facility
 * @property-read Workspace $workspace
 *
 * Relations
 * @property Project $project
 * @property-read HeramsResponseInterface[] $responses
 */
class Response extends ActiveRecord
{
    public static function find(): ResponseQuery
    {
        return \Yii::createObject(ResponseQuery::class, [get_called_class()]);
    }

    public function getFacility(): ActiveQuery
    {
        return $this->hasOne(Facility::class, ['id' => 'facility_id']);
    }

    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, ['id' => 'tool_id'])->via('workspace');
    }

    public function getWorkspace(): ActiveQuery
    {
        return $this->hasOne(Workspace::class, ['id' => 'workspace_id']);
    }

    public static function instantiate($row)
    {
        if (!empty($row['hf_id'])) {
            return new ResponseForLimesurvey();
        }

        return parent::instantiate($row);
    }

    public static function tableName(): string
    {
        return '{{%response}}';
    }
}
