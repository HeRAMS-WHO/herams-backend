<?php

declare(strict_types=1);

namespace prime\tests\helpers;

use prime\helpers\MigrationHelper;
use yii\base\NotSupportedException;
use yii\db\Migration;

final class MigrationHelperTest extends \Codeception\Test\Unit
{
    private Migration $migration;
    private MigrationHelper $helper;
    private string $table = 'test_table_not_null';
    private string $table_with_nulls = 'test_table_null';
    protected function _before()
    {
        parent::_before();
        $this->migration = new Migration();
        $this->helper = new MigrationHelper($this->migration);
        \Yii::$app->db->createCommand("create table $this->table (dob int(1) not null, gender datetime not null)")->execute();
        \Yii::$app->db->createCommand("create table $this->table_with_nulls (dob int(1) null, gender datetime  null)")->execute();

    }

    protected function _after()
    {
        \Yii::$app->db->createCommand("drop table $this->table")->execute();
        \Yii::$app->db->createCommand("drop table $this->table_with_nulls")->execute();
    }

    public function testChangeColumnFromDatetimeToInt(): void
    {
        $this->helper->changeColumnFromDatetimeToInt($this->table, 'gender');
        $schema =  \Yii::$app->db->getTableSchema($this->table, true);
        $columnSchema = $schema->getColumn("gender");
        $this->assertTrue($columnSchema->dbType !== 'datetime');
        $this->assertTrue($columnSchema->dbType == 'int');

        $this->expectException(NotSupportedException::class);
        $this->helper->changeColumnFromDatetimeToInt($this->table, 'gender');
    }

    public function testChangeColumnFromIntToDatetime(): void
    {
        $this->helper->changeColumnFromIntToDatetime($this->table, 'dob');
        $schema = $this->migration->db->getTableSchema($this->table, true);
        $columnSchema = $schema->getColumn('dob');
        $this->assertTrue($columnSchema->dbType !== 'int');
        $this->assertTrue($columnSchema->dbType == 'datetime');

        $this->expectException(NotSupportedException::class);
        $this->helper->changeColumnFromIntToDatetime($this->table, 'dob');
    }

    public function testChangeColumnFromDatetimeToIntWithNull(): void
    {
        $this->helper->changeColumnFromDatetimeToIntWithNull($this->table_with_nulls, 'gender');
        $schema = $this->migration->db->getTableSchema($this->table_with_nulls, true);
        $columnSchema = $schema->getColumn('gender');
        $this->assertTrue($columnSchema->dbType !== 'datetime');
        $this->assertTrue($columnSchema->dbType == 'int');

        $this->expectException(NotSupportedException::class);
        $this->helper->changeColumnFromDatetimeToIntWithNull($this->table_with_nulls, 'gender');
    }

    public function testChangeColumnFromIntToDatetimeWithNull(): void
    {
        $this->helper->changeColumnFromIntToDatetimeWithNull($this->table_with_nulls, 'dob');
        $schema = $this->migration->db->getTableSchema($this->table_with_nulls, true);
        $columnSchema = $schema->getColumn('dob');
        $this->assertTrue($columnSchema->dbType !== 'int');
        $this->assertTrue($columnSchema->dbType == 'datetime');

        $this->expectException(NotSupportedException::class);
        $this->helper->changeColumnFromIntToDatetimeWithNull($this->table_with_nulls, 'dob');
    }
}
