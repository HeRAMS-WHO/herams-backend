<?php

declare(strict_types=1);

namespace prime\helpers;

use prime\interfaces\ColumnDefinition;
use prime\interfaces\HeramsResponseInterface;
use prime\interfaces\WriterInterface;
use Psr\Http\Message\StreamInterface;
use Vtiful\Kernel\Excel;

use function GuzzleHttp\Psr7\stream_for;

class PeclWriter implements WriterInterface
{
    private Excel $file;

    private $buffer = [];

    private float $time = 0;

    private float $mapping = 0;

    public function __construct()
    {
        $excel = new Excel([
            'path' => sys_get_temp_dir(),
        ]);
        $this->file = $excel->constMemory('xlsx' . md5(microtime()) . '.xlsx');
        $this->file = $excel->fileName('tutorial01.xlsx', 'sheet1');
    }

    private function writeRow(array $data): void
    {
        $this->buffer[] = $data;
        if (count($this->buffer) > 500) {
            $this->flush();
        }
    }

    private function flush(): void
    {
        $start = microtime(true);
        if (! empty($this->buffer)) {
            $this->file->data($this->buffer);
            $this->buffer = [];
        }
        $this->time += microtime(true) - $start;
    }

    public function writeRecord(HeramsResponseInterface $record, ColumnDefinition ...$columns): void
    {
        $row = [];
        $this->mapping -= microtime(true);
        foreach ($columns as $i => $column) {
            $row[] = $column->getValue($record);
        }
        $this->mapping += microtime(true);
        $this->writeRow($row);
    }

    public function writeHeader(string ...$headers): void
    {
        $this->writeRow($headers);
    }

    public function getStream(): StreamInterface
    {
        $this->flush();
        \Yii::error('Time spent writing: ' . $this->time);
        \Yii::error('Time spent mapping: ' . $this->mapping);
        $file = $this->file->output();
        return stream_for(fopen($file, 'r'));
    }

    public function getMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }
}
