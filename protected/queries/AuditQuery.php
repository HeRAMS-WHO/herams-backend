<?php
declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;
use prime\models\ActiveRecord;
use prime\models\ar\Audit;
use prime\objects\enums\AuditEvent;
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
            'event' => AuditEvent::insert()
        ]);
    }

    /**
     * @param class-string $class
     */
    public function forModelClass(string $class): self
    {
        return $this->andWhere(['subject_name' => $class]);
    }

    public function forSubjectId(int|Expression $id): self
    {
        return $this->andWhere(['subject_id' => $id]);
    }
}
