<?php
declare(strict_types=1);

namespace prime\helpers;

use GuzzleHttp\Psr7\LazyOpenStream;
use GuzzleHttp\Psr7\Stream;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use prime\interfaces\ColumnDefinition;
use prime\interfaces\HeramsResponseInterface;
use prime\interfaces\WriterInterface;
use Psr\Http\Message\StreamInterface;
use function GuzzleHttp\Psr7\stream_for;
use function iter\map;
use function iter\toArray;

class XlsxWriter implements WriterInterface
{
    /**
     * @var Spreadsheet
     */
    private $file;
    private $rows;

    public function __construct()
    {
        $this->reset();
    }


    private function reset()
    {
        $this->file = new Spreadsheet();
        $this->rows = 1;
    }


    private function writeRow(iterable $data): void
    {
        $sheet = $this->file->getActiveSheet();
        $column = 0;
        foreach ($data as $value) {
            $sheet->setCellValueByColumnAndRow($column, $this->rows, $value);
            $column++;
        }
        $this->rows++;
    }

    public function writeRecord(HeramsResponseInterface $record, ColumnDefinition ...$columns): void
    {
        $this->writeRow(map(static function (ColumnDefinition $column) use ($record) {
            return $column->getValue($record);
        }, $columns));
    }

    public function writeTextHeader(ColumnDefinition ...$columns): void
    {
        $this->writeRow(map(static function (ColumnDefinition $column): string {
            return $column->getHeaderText();
        }, $columns));
    }

    public function writeCodeHeader(ColumnDefinition ...$columns): void
    {
        $this->writeRow(map(static function (ColumnDefinition $column): string {
            return $column->getHeaderCode();
        }, $columns));
    }

    public function getStream(): StreamInterface
    {
        $writer = new Xlsx($this->file);
        $tmpfile = tempnam(sys_get_temp_dir(), 'xlsx');
        $writer->save($tmpfile);
        $this->reset();
        return new LazyOpenStream($tmpfile, 'r');
    }

    public function getMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }
}
