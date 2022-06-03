<?php

declare(strict_types=1);

namespace prime\components;

use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\web\Application;

class MaintenanceMode implements BootstrapInterface
{
    public const MAINTENANCE_MODE = 'MAINTENANCE_MODE';

    public function bootstrap($application)
    {
        if ($application instanceof Application) {
            $this->checkMaintenanceMode($application);
        }
    }

    public function checkMaintenanceMode(Application $application)
    {
        if ($application->cache->exists(self::MAINTENANCE_MODE)) {
            $application->catchAll = ['site/maintenance'];
            $application->set('session', null);
            $application->user->enableSession = false;
            $application->set('db', null);
        }
    }
}
