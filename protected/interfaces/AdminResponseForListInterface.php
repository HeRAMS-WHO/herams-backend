<?php

declare(strict_types=1);

namespace prime\interfaces;

use herams\common\values\ResponseId;

interface AdminResponseForListInterface
{
    public const ID = "id";

    public const NAME = "name";

    public const DATE_OF_UPDATE = 'dateOfUpdate';

    public const ATTRIBUTES = [self::ID, self::NAME, self::DATE_OF_UPDATE];

    public function getDateOfUpdate(): null|\DateTimeInterface;

    public function getId(): ResponseId;

    public function getName(): null|string;
}
