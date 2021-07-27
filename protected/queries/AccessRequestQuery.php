<?php
declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;

/**
 * Class AccessRequestQuery
 * @package prime\queries
 */
class AccessRequestQuery extends ActiveQuery
{
    public function createdBy(int $userId): self
    {
        return $this->andWhere(['created_by' => $userId]);
    }

    /**
     * For now there is no expiration desired
     */
    public function notExpired(): self
    {
        return $this;
    }

    public function withResponse(): self
    {
        return $this->andWhere(['not', ['response' => null]]);
    }

    public function withoutResponse(): self
    {
        return $this->andWhere(['response' => null]);
    }
}
