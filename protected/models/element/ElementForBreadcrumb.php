<?php
declare(strict_types=1);

namespace prime\models\element;

use prime\interfaces\element\ElementForBreadcrumbInterface;
use prime\models\ar\Element;
use prime\traits\BreadcrumbTrait;
use prime\values\PageId;

class ElementForBreadcrumb implements ElementForBreadcrumbInterface
{
    use BreadcrumbTrait;

    private PageId $pageId;

    public function __construct(
        Element $model
    ) {
        $this->label = $model->getTitle();
        $this->pageId = new PageId($model->page_id);
        $this->url = ['/element/preview', 'id' => $model->id];
    }

    public function getPageId(): PageId
    {
        return $this->pageId;
    }
}
