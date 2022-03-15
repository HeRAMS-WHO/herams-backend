<?php

declare(strict_types=1);
namespace prime\tests\helpers;

use prime\helpers\MigrationHelper;
use yii\base\NotSupportedException;
use yii\db\Migration;
use function PHPUnit\Framework\assertTrue;

final class MigrationHelperTest extends \Codeception\Test\Unit
{
    private $migration;
    private $helper;
    private $table = 'test_table3';
    
    protected function _before()
    {
        $this->migration = new Migration();
        $this->helper = new MigrationHelper($this->migration);
        $this->migration->createTable($this->table, [
            'dob' => 'datetime',
            'gender' => 'int'
        ]);
    }

    protected function _after()
    {
        $this->migration->dropTable($this->table);
    }

    // tests
    public function testChangeColumnFromDatetimeToInt(): void
    {
        $this->helper->changeColumnFromDatetimeToInt($this->table, 'dob');
        $schema = $this->getDb()->getTableSchema($this->table, true);
        $columnSchema = $schema->getColumn('dob');
        $this->assertTrue($columnSchema->dbType !== 'datetime');
        
        $this->expectException(NotSupportedException::class);
        $this->helper->changeColumnFromDatetimeToInt($this->table, 'dob');
    }
}
