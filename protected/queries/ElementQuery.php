<?php

declare(strict_types=1);

namespace prime\queries;

use herams\common\queries\ActiveQuery;

class ElementQuery extends ActiveQuery
{
    public function getNextSortValue(): int
    {
        return ($this->select('max(sort)')->scalar() ?? 0) + 1;
    }
}
