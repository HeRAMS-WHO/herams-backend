<?php

declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;

class ElementQuery extends ActiveQuery
{
    public function getNextSortValue(): int
    {
        return ($this->select('max(sort)')->scalar() ?? 0) + 1;
    }
}
