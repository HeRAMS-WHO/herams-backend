<?php
declare(strict_types=1);

namespace prime\models\elements;

use prime\interfaces\element\ElementForBreadcrumbInterface;
use prime\models\ar\Element;
use prime\traits\BreadcrumbTrait;
use prime\values\PageId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
class ElementForBreadcrumb implements ElementForBreadcrumbInterface
{
    use BreadcrumbTrait;

    private PageId $pageId;

    public function __construct(
        Element $model
    ) {
        $this->label = $model->getDisplayField();
        $this->pageId = new PageId($model->page_id);
        $this->url = ['/element/preview', 'id' => $model->id];
    }

    public function getPageId(): PageId
    {
        return $this->pageId;
    }
}
