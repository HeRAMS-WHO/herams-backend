<?php
declare(strict_types=1);

namespace prime\interfaces;

use Psr\Http\Message\StreamInterface;

interface WriterInterface
{
    public function writeRecord(HeramsResponseInterface $record, ColumnDefinition ...$columns): void;
    public function writeTextHeader(ColumnDefinition ...$columns): void;
    public function writeCodeHeader(ColumnDefinition ...$columns): void;

    /**
     * This should relinquish any underlying resources.
     * Any writing after this should happen on a new stream.
     * @return StreamInterface
     */
    public function getStream(): StreamInterface;

    public function getMimeType(): string;
}
