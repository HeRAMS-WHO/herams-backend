<?php


namespace prime\interfaces;


use prime\models\ar\Element;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

interface PageInterface
{

    /**
     * @return PageInterface[]
     */
    public function getChildPages(SurveyInterface $survey): iterable;

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