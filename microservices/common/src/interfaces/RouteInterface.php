<?php
declare(strict_types=1);

namespace herams\common\interfaces;

use yii\helpers\Url;

/**
 * A route that may be passed to Yii's UrlHelper.
 * @see Url::toRoute()
 */
interface RouteInterface
{
    public function getValue(): array;
}
