<?php
declare(strict_types=1);

namespace prime\helpers;

use Box\Spout\Common\Helper\GlobalFunctionsHelper;
use Box\Spout\Writer\Common\Creator\InternalEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\ODS\Writer;
use GuzzleHttp\Psr7\LazyOpenStream;
use prime\components\ManagerFactory;
use prime\interfaces\ColumnDefinition;
use prime\interfaces\HeramsResponseInterface;
use prime\interfaces\WriterInterface;
use Psr\Http\Message\StreamInterface;
use function GuzzleHttp\Psr7\stream_for;
use function iter\map;

class OdsWriter implements WriterInterface
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
        // Set up the cache

        $this->writer = WriterEntityFactory::createODSWriter();
        $this->stream = stream_for('');
        $this->filename = tempnam(sys_get_temp_dir(), 'ods');
        $this->writer->openToFile($this->filename);
    }


    private function writeRow(iterable $data): void
    {
        $row = WriterEntityFactory::createRow([]);
        foreach ($data as $value) {
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

    public function writeHeader(string ...$headers): void
    {
        $this->writeRow($headers);
    }
    public function getStream(): StreamInterface
    {
        $this->writer->close();
        return new LazyOpenStream($this->filename, 'r');
    }

    public function getMimeType(): string
    {
        return 'application/vnd.oasis.opendocument.spreadsheet';
    }
}
