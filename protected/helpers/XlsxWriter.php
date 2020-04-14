<?php
declare(strict_types=1);

namespace prime\helpers;

use Box\Spout\Common\Helper\GlobalFunctionsHelper;
use Box\Spout\Writer\Common\Creator\InternalEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\XLSX\Creator\HelperFactory as XLSXHelperFactory;
use Box\Spout\Writer\XLSX\Creator\ManagerFactory as XLSXManagerFactory;
use Box\Spout\Writer\XLSX\Manager\OptionsManager as XLSXOptionsManager;
use Box\Spout\Writer\XLSX\Writer;
use GuzzleHttp\Psr7\LazyOpenStream;
use prime\components\ManagerFactory;
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
        $styleBuilder = new StyleBuilder();
        $optionsManager = new XLSXOptionsManager($styleBuilder);
        $globalFunctionsHelper = new GlobalFunctionsHelper();

        $helperFactory = new XLSXHelperFactory();
        $managerFactory = new ManagerFactory(new InternalEntityFactory(), $helperFactory);

        $this->writer = new Writer($optionsManager, $globalFunctionsHelper, $helperFactory, $managerFactory);
        $this->stream = stream_for('');
        $this->filename = tempnam(sys_get_temp_dir(), 'xslx');
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
