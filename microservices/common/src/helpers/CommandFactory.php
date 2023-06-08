<?php

declare(strict_types=1);

namespace herams\common\helpers;

use herams\common\interfaces\CommandFactoryInterface;
use yii\db\Command;

final class CommandFactory implements CommandFactoryInterface
{
    public function createCommand($sql = null, $params = []): Command
    {
        return \Yii::$app->db->createCommand($sql, $params);
    }
}
