<?php

declare(strict_types=1);

namespace prime\components;

use SamIT\Yii2\VirtualFields\VirtualFieldQueryTrait;

/**
 * @codeCoverageIgnore
 */
class ActiveQuery extends \yii\db\ActiveQuery
{
    use VirtualFieldQueryTrait;

    public function count($q = '*', $db = null): int
    {
        return (int) parent::count($q, $db);
    }
}
