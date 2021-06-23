<?php
declare(strict_types=1);

namespace prime\models\elements;

use prime\models\ar\Element;
use prime\values\ElementId;
use prime\values\PageId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
class ForBreadcrumb implements \prime\interfaces\element\ForBreadcrumb
{
    private ElementId $id;
    private PageId $pageId;
    private string $title;

    public function __construct(
        Element $model
    ) {
        $this->id = new ElementId($model->id);
        $this->pageId = new PageId($model->page_id);
        $this->title = $model->getDisplayField();
    }

    public function getId(): ElementId
    {
        return $this->id;
    }

    public function getPageId(): PageId
    {
        return $this->pageId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
