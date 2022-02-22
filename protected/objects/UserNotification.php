<?php

declare(strict_types=1);

namespace prime\objects;

use prime\interfaces\UserNotificationInterface;

class UserNotification implements UserNotificationInterface
{
    public function __construct(
        private string $_title,
        private ?array $_url
    ) {
    }

    public function getTitle(): string
    {
        return $this->_title;
    }

    public function getUrl(): ?array
    {
        return $this->_url;
    }

    public function setTitle(string $title): UserNotification
    {
        $this->_title = $title;
        return $this;
    }

    public function setUrl(?array $url): UserNotification
    {
        $this->_url = $url;
        return $this;
    }
}
