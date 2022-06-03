<?php

declare(strict_types=1);

namespace prime\values;

use prime\models\ar\Page;

class PageId extends IntegerId
{
    public static function fromPage(Page $page): self
    {
        if (! is_integer($page->id)) {
            throw new \InvalidArgumentException('Page must have an id');
        }
        return new self($page->id);
    }
}
