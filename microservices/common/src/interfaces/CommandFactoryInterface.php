<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use yii\db\Command;

interface CommandFactoryInterface
{
    public function createCommand($sql = null, $params = []): Command;
}
