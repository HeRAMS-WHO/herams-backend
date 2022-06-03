<?php

declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;
use Ramsey\Uuid\Uuid;

class FacilityQuery extends ActiveQuery
{
    public function withIdentity(string $id): self
    {
        return $this->andWhere([
            'id' => Uuid::fromString($id)->getBytes(),
        ]);
    }
}
