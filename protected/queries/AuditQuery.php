<?php

declare(strict_types=1);

namespace prime\queries;

use herams\common\enums\AuditEvent;
use herams\common\models\ActiveRecord;
use herams\common\queries\ActiveQuery;
use yii\db\Expression;

class AuditQuery extends ActiveQuery
{
    public function forModel(ActiveRecord $model): self
    {
        return $this->forModelClass(get_class($model))->forSubjectId($model->primaryKey);
    }

    public function created(): self
    {
        return $this->andWhere([
            'event' => AuditEvent::Insert->value,
        ]);
    }

    /**
     * @param class-string $class
     */
    public function forModelClass(string $class): self
    {
        return $this->andWhere([
            'subject_name' => $class,
        ]);
    }

    public function forSubjectId(int|Expression $id): self
    {
        return $this->andWhere([
            'subject_id' => $id,
        ]);
    }
}
