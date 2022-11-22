<?php

namespace herams\common\interfaces;

use herams\common\domain\element\Element;

interface PageInterface
{
    /**
     * @return PageInterface[]
     */
    public function getChildPages(): iterable;

    /**
     * @return Element[]
     */
    public function getChildElements(): iterable;

    public function getTitle(): string;

    public function getId(): int;

    public function getParentId(): ?int;

    public function getParentPage(): ?PageInterface;

    public function filterResponses(iterable $responses): iterable;
}
