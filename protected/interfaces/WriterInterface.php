<?php

declare(strict_types=1);

namespace prime\interfaces;

use herams\common\interfaces\HeramsResponseInterface;
use Psr\Http\Message\StreamInterface;

interface WriterInterface
{
    public function writeRecord(HeramsResponseInterface $record, ColumnDefinition ...$columns): void;

    public function writeHeader(string ...$headers): void;

    /**
     * This should relinquish any underlying resources.
     * Any writing after this should happen on a new stream.
     */
    public function getStream(): StreamInterface;

    public function getMimeType(): string;
}
