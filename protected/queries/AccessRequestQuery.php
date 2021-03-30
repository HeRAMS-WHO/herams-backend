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
    public function withoutResponse(): self
    {
        return $this->andWhere(['response' => null]);
    }
}
