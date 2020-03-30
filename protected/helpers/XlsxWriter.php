<?php
declare(strict_types=1);

namespace prime\helpers;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\XLSX\Writer;
use GuzzleHttp\Psr7\LazyOpenStream;
use prime\interfaces\ColumnDefinition;
use prime\interfaces\HeramsResponseInterface;
use prime\interfaces\WriterInterface;
use Psr\Http\Message\StreamInterface;
use function GuzzleHttp\Psr7\stream_for;
use function iter\map;

class XlsxWriter implements WriterInterface
{
    /**
     * @var Writer
     */
    private $writer;

    private $filename;

    public function __construct()
    {
        $this->reset();
    }


    private function reset()
    {
        ini_set('max_execution_time', '0');
        // Set up the cache
        $this->writer = WriterEntityFactory::createXLSXWriter();
        $this->stream = stream_for('');
        $this->filename = tempnam(sys_get_temp_dir(), 'xslx');
        $this->writer->openToFile($this->filename);
    }


    private function writeRow(iterable $data): void
    {
        $row = WriterEntityFactory::createRow([]);
        foreach($data as $value) {
            $row->addCell(WriterEntityFactory::createCell($value));
        }
        $this->writer->addRow($row);
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
        $this->writer->close();
        return new LazyOpenStream($this->filename, 'r');
    }

    public function getMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }
}
