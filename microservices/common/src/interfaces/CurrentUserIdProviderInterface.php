<?php
declare(strict_types=1);

namespace herams\common\interfaces;

use herams\common\values\UserId;

interface CurrentUserIdProviderInterface
{

    public function getUserId(): UserId;

}
