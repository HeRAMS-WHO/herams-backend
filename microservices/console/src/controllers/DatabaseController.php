<?php

namespace herams\console\controllers;

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

        $db = \Yii::$app->db;
        // Step 1. Run the migrations.
        $return = 0;
        if ($redo > 0) {
            passthru($_SERVER['PHP_SELF'] . ' migrate/redo --silentExitOnException=0 --interactive=0 --color=1 ' . $redo, $return);
        }

        if ($return !== 0) {
            $this->stderr("Redo failed... stopping.\n", Console::FG_RED);
            return;
        }

        passthru($_SERVER['PHP_SELF'] . ' migrate/up --silentExitOnException=0 --interactive=0 --color=1', $return);
        if ($return !== 0) {
            $this->stderr("Migrations failed... stopping.\n", Console::FG_RED);
            return;
        }

        // Get table names.
        $tables = $db->schema->getTableNames('', true);
        sort($tables);
        $output = fopen(\Yii::getAlias('@tests/_data/db/10_table-structure.sql'), 'w');
        fwrite($output, "SET FOREIGN_KEY_CHECKS=0;\n");
        foreach ($tables as $table) {
            try {
                $command = $db->createCommand("SHOW CREATE TABLE `{$table}`;");
                $result = $command->queryOne()["Create Table"];
                $result = \preg_replace('/( AUTO_INCREMENT=\d+)/', "", $result);
                fwrite($output, $result);
                fwrite($output, ";\n\n");
            } catch (Exception $e) {
                $this->stderr("Skipped table {$table}");
            }
        }
        fwrite($output, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($output);

        // Step 3. Export all table data
        /** @var TableSchema $schema */
        foreach ($db->schema->tableSchemas as $schema) {
            if (in_array($schema->name, ['session'])) {
                continue;
            }
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
    }
}
