<?php

declare(strict_types=1);

namespace herams\console\controllers;

use yii\helpers\Console;

final class MigrateController extends \yii\console\controllers\MigrateController
{
    protected function includeMigrationFile($class)
    {
        // Disable manual inclusion. It is not needed for namespaced files.
    }

    protected function getMigrationHistory($limit)
    {
        $namespacedHistory = [];
        foreach (parent::getMigrationHistory($limit) as $migrationName => $time) {
            if (! str_contains($migrationName, '\\')
                && class_exists($this->migrationNamespaces[0] . '\\' . $migrationName)
            ) {
                $namespacedHistory[$this->migrationNamespaces[0] . '\\' . $migrationName] = $time;
            // Not namespaced
            } else {
                $namespacedHistory[$migrationName] = $time;
            }
        };
        return $namespacedHistory;
    }
    protected function Seeder(){
        Console::stdout("Enter the name of the migration to seed: ");
    }
}
