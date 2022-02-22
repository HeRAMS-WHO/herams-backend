<?php

declare(strict_types=1);

namespace prime\helpers;

use Carbon\Carbon;
use yii\base\NotSupportedException;
use yii\db\ColumnSchemaBuilder;
use yii\db\Connection;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\SchemaBuilderTrait;

class MigrationHelper
{
    use SchemaBuilderTrait;

    public function __construct(private Migration $migration)
    {
    }

    public function changeColumnFromDatetimeToInt(string $table, string $column): void
    {
        $schema = $this->getDb()->getTableSchema($table, true);
        $columnSchema = $schema->getColumn($column);
        if ($columnSchema->dbType !== 'datetime') {
            throw new NotSupportedException("Column is currently not datetime: {$columnSchema->dbType}");
        }
        if ($columnSchema->allowNull) {
            throw new NotSupportedException("Column allows null, please use changeColumnFromDatetimeToIntWithNull instead");
        }

        $this->createTempIntColumnWithData($table, $column);
        $this->alterTempColumn($table, $column, $this->integer()->notNull());
        $this->finalizeTempColumn($table, $column);
    }

    public function changeColumnFromIntToDatetime(
        string $table,
        string $column
    ): void {
        $schema = $this->getDb()->getTableSchema($table, true);
        $columnSchema = $schema->getColumn($column);
        if ($columnSchema->dbType !== 'int') {
            throw new NotSupportedException("Column is currently not int");
        }
        if ($columnSchema->allowNull) {
            throw new NotSupportedException("Column allows null, please use changeColumnFromIntToDatetimeWithNull instead");
        }

        $this->createTempDateTimeColumnWithData($table, $column);
        $this->alterTempColumn($table, $column, $this->dateTime()->notNull());
        $this->finalizeTempColumn($table, $column);
    }

    private function getTempColumnName(string $sourceColumn): string
    {
        return "temp_{$sourceColumn}";
    }

    private function finalizeTempColumn(string $table, string $sourceColumn): void
    {
        $tempColumn = $this->getTempColumnName($sourceColumn);

        $this->migration->renameColumn($table, $sourceColumn, "old_{$sourceColumn}");
        $this->migration->renameColumn($table, $tempColumn, $sourceColumn);
        $this->migration->dropColumn($table, "old_{$sourceColumn}");
    }

    private function alterTempColumn(string $table, string $sourceColumn, ColumnSchemaBuilder $type): void
    {
        $this->migration->alterColumn($table, $this->getTempColumnName($sourceColumn), $type);
    }

    private function createTempIntColumnWithData(string $table, string $column): void
    {
        $schema = $this->getDb()->getTableSchema($table, true);
        // Create a new column with a temporary name.
        $tempColumn = $this->getTempColumnName($column);
        if ($schema->getColumn($tempColumn) !== null) {
            $this->migration->dropColumn($table, $tempColumn);
        }
        // We set a default since the column doesn't allow nulls.
        $this->migration->addColumn($table, $tempColumn, $this->integer()->null());
        $this->migration->update($table, [
            $tempColumn => new Expression("UNIX_TIMESTAMP([[$column]])")
        ]);
    }

    private function createTempDateTimeColumnWithData(string $table, string $column): void
    {
        $schema = $this->getDb()->getTableSchema($table, true);
        // Create a new column with a temporary name.
        $tempColumn = $this->getTempColumnName($column);
        if ($schema->getColumn($tempColumn) !== null) {
            $this->migration->dropColumn($table, $tempColumn);
        }
        // We set a default since the column doesn't allow nulls.
        $this->migration->addColumn($table, $tempColumn, $this->dateTime()->null());
        $this->migration->update($table, [
            $tempColumn => new Expression("FROM_UNIXTIME([[$column]])")
        ]);
    }

    public function changeColumnFromDatetimeToIntWithNull(string $table, string $column): void
    {
        $schema = $this->getDb()->getTableSchema($table, true);
        $columnSchema = $schema->getColumn($column);
        if ($columnSchema->dbType !== 'datetime') {
            throw new NotSupportedException("Column is currently not datetime: {$columnSchema->dbType}");
        }
        if (!$columnSchema->allowNull) {
            throw new NotSupportedException("Column allows null, please use changeColumnFromDatetimeToInt instead");
        }

        $this->createTempIntColumnWithData($table, $column);
        $this->finalizeTempColumn($table, $column);
    }

    public function changeColumnFromIntToDatetimeWithNull(
        string $table,
        string $column
    ): void {
        $schema = $this->getDb()->getTableSchema($table, true);
        $columnSchema = $schema->getColumn($column);
        if ($columnSchema->dbType !== 'int') {
            throw new NotSupportedException("Column is currently not int");
        }
        if (!$columnSchema->allowNull) {
            throw new NotSupportedException("Column allows null, please use changeColumnFromIntToDatetime instead");
        }
        $this->createTempDateTimeColumnWithData($table, $column);
        $this->finalizeTempColumn($table, $column);
    }

    protected function getDb(): Connection
    {
        return $this->migration->db;
    }
}
