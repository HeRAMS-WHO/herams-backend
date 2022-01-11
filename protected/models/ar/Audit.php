<?php

declare(strict_types=1);

namespace prime\models\ar;

use prime\models\ActiveRecord;
use prime\queries\AuditQuery;

/**
 * @property string $subject_name
 * @property int $subject_id
 * @property string $event
 * @property \DateTime $created_at
 * @property int $created_by
 */
class Audit extends ActiveRecord
{
    public static function find(): AuditQuery
    {
        return new AuditQuery(static::class);
    }

    public static function labels(): array
    {
        return [
            'subject_name' => \Yii::t('app', "Type of the audited entry"),
            'subject_id' => \Yii::t('app', "Id of the audited entry"),
            'event' => \Yii::t('app', 'Type of the event')
            ] + parent::labels();
    }
}
