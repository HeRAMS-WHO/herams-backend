<?php

declare(strict_types=1);

namespace prime\commands;

use prime\components\MaintenanceMode;
use yii\caching\Cache;
use yii\caching\CacheInterface;
use yii\console\Controller;
use yii\helpers\Console;

class MaintenanceController extends Controller
{
    private function printStatus(CacheInterface $cache): void
    {
        if ($cache->exists(MaintenanceMode::MAINTENANCE_MODE)) {
            $enabledTime = $cache->get(MaintenanceMode::MAINTENANCE_MODE);
            echo strtr("Maintenance mode has been {maintenanceStatus} since {since}, the platform is {platformStatus}\n", [
                '{maintenanceStatus}' => Console::ansiFormat("ACTIVE", [Console::FG_GREEN]),
                '{since}' => date('d-m-Y H:i:s', $enabledTime),
                '{platformStatus}' => Console::ansiFormat("NOT REACHABLE", [Console::FG_RED])
            ]);
        } else {
            echo strtr("Maintenance mode is currently {maintenanceStatus}, the platform should be {platformStatus}\n", [
                '{maintenanceStatus}' => Console::ansiFormat("NOT ACTIVE", [Console::FG_RED]),
                '{platformStatus}' => Console::ansiFormat("REACHABLE", [Console::FG_GREEN])
            ]);
        }
    }

    public function actionIndex(CacheInterface $cache): void
    {
        $this->printStatus($cache);
    }

    public function actionEnable(CacheInterface $cache, int $duration = null): void
    {
        $cache->set(MaintenanceMode::MAINTENANCE_MODE, time(), $duration);
        $this->printStatus($cache);
    }

    public function actionDisable(CacheInterface $cache): void
    {
        $cache->delete(MaintenanceMode::MAINTENANCE_MODE);
        $this->printStatus($cache);
    }
}
