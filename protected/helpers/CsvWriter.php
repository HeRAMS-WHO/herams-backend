<?php
declare(strict_types=1);

namespace prime\helpers;

use GuzzleHttp\Psr7\Stream;
use prime\interfaces\ColumnDefinition;
use prime\interfaces\HeramsResponseInterface;
use prime\interfaces\WriterInterface;
use Psr\Http\Message\StreamInterface;
use function iter\map;
use function iter\toArray;

class CsvWriter implements WriterInterface
{
    private $stream;
    private $size;

    public function __construct()
    {
        $this->reset();
    }


    private function reset()
    {
        $this->stream = fopen('php://temp', 'w');
        $this->size = 0;
    }
    private function fputcsv(iterable $data): void
    {
        $result = fputcsv($this->stream, is_array($data) ? $data : toArray($data));
        if ($result === false) {
            throw new \RuntimeException('Write failed');
        }
        $this->size += $result;
    }

    public function writeRecord(HeramsResponseInterface $record, ColumnDefinition ...$columns): void
    {
        $this->fputcsv(map(static function (ColumnDefinition $column) use ($record) {
            return $column->getValue($record);
        }, $columns));
    }

    public function writeHeader(string ...$headers): void
    {
        $this->fputcsv($headers);
    }

    public function getStream(): StreamInterface
    {
        rewind($this->stream);
        $result = new Stream($this->stream, ['size' => $this->size]);
        $this->reset();
        return $result;
    }

    public function getMimeType(): string
    {
        return 'text/csv';
    }
}
