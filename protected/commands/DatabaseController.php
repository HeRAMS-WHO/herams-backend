<?php

namespace prime\commands;

use Ifsnop\Mysqldump\Mysqldump;
use yii\console\Controller;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\Query;
use yii\db\TableSchema;
use yii\helpers\Console;

/**
 * Class DatabaseController
 * @codeCoverageIgnore This file is not used in production
 */
class DatabaseController extends Controller
{
    private function runMigrateCommand(string $command = 'up', string $params = ''): void
    {
        $cmd = "YII_ENV=dev {$_SERVER['PHP_SELF']} migrate/$command --interactive=0 --color=1 $params";
        $this->stdout("Running command: $cmd\n", Console::FG_CYAN);
        passthru($cmd, $return);

        if ($return !== 0) {
            $this->stderr("Subcommand failed with exit code:  $return\n", Console::FG_RED);
            exit(2);
        }
    }

    private function connectWithTimeout(Connection $db, int $timeout)
    {
        $start = microtime(true);
        $this->stdout('Waiting for database to come up.', Console::FG_YELLOW);
        while (microtime(true) - $start < $timeout) {
            try {
                $this->stdout('.', Console::FG_CYAN);
                $db->open();
                break;
            } catch (Exception $e) {
                sleep(1);
            }
        }
        $db->open();
        $this->stdout(". OK\n", Console::FG_GREEN);
    }

    public function actionUpdateTest(Connection $db, int $redo = 0)
    {
        if (YII_ENV != 'test') {
            throw new \Exception("This command only works in the test environment");
        }

        $this->connectWithTimeout($db, 30);
        // Step 1. Run module migrations
        $this->runMigrateCommand('up', '--migrationPath=@vendor/dektrium/yii2-user/migrations');
        $this->runMigrateCommand('up', '--migrationPath=@yii/rbac/migrations');

        // Step 2. Run app migrations.
        if ($redo > 0) {
            $this->runMigrateCommand('redo ' . $redo);
        }

        $this->runMigrateCommand('up');


        // Get table names.
        $tables = $db->schema->getTableNames('', true);

        // Step 2. Dump mysql file.
        $dump = new Mysqldump($db->dsn, $db->username, $db->password, [
            'add-drop-table' => true,
            'no-data' => true,
            'skip-dump-date' => true,
        ]);
        $dump->start(\Yii::getAlias('@tests/_data/db/10_table-structure.sql'));

        // Step 3. Export all table data
        /** @var TableSchema $schema */
        foreach ($db->schema->tableSchemas as $schema) {
            $columns = array_flip($schema->columnNames);
            $ddl = $db->createCommand('SHOW CREATE TABLE ' . $db->quoteTableName($schema->name))->queryOne();
            if (!isset($ddl['Create Table'])) {
                continue;
            }
            foreach (explode("\n", $ddl['Create Table']) as $line) {
                if (preg_match('/^\s*`(?<column>.*)`.*GENERATED.*$/', $line, $matches)) {
                    unset($columns[$matches['column']]);
                }
            }
            $columns = array_keys($columns);
            $q = new Query();
            $q->select($columns);
            $q->from($schema->name);
            if (($count = $q->count()) > 0) {
                $file = fopen(\Yii::getAlias("@tests/_data/db/50_{$schema->name}.sql"), 'w');
                fwrite($file, "SET FOREIGN_KEY_CHECKS=0;\n");
                fwrite($file, "SET NAMES 'utf8';\n");
                foreach ($q->each(1000, $db) as $r) {
                    foreach ($r as $column => &$value) {
                        $value = $schema->getColumn($column)->phpTypecast($value);
                    }
                    fwrite($file, $db->createCommand()->insert($schema->name, $r)->getRawSql() . ";\n");
                    echo '.';
                }

                fwrite($file, "SET FOREIGN_KEY_CHECKS=1;\n");
                fclose($file);
            }
            $this->stdout("\n" . $schema->name . ': ' . $count . "\n", Console::FG_CYAN);
        }

        // Check tablenames.
        foreach (array_diff($tables, $db->schema->getTableNames('', true)) as $removedTable) {
            $this->stdout("Table removed by migration: $removedTable, removing the data file...", Console::FG_YELLOW);
            if (unlink(\Yii::getAlias("@tests/_data/50_{$removedTable}.sql"))) {
                $this->stdout("OK\n", Console::FG_GREEN);
            } else {
                $this->stdout("Fail\n", Console::FG_RED);
            }
        }

        $this->stdout("\nDone.\n", Console::FG_GREEN);
        return;
    }
}
