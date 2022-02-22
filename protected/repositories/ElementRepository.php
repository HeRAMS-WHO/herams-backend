<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\interfaces\element\ElementForBreadcrumbInterface as ForBreadcrumbInterface;
use prime\models\ar\Element;
use prime\models\element\ElementForBreadcrumb;
use prime\values\ElementId;

class ElementRepository
{
    public function retrieveForBreadcrumb(ElementId $id): ForBreadcrumbInterface
    {
        $record = Element::findOne(['id' => $id]);
        return new ElementForBreadcrumb($record);
    }
}
