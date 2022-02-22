<?php

declare(strict_types=1);

namespace prime\interfaces;

interface UserNotificationInterface
{
    public function getTitle(): string;
    public function getUrl(): ?array;
}
