<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\interfaces\AccessCheckInterface;
use prime\interfaces\element\ForBreadcrumb as ForBreadcrumbInterface;
use prime\models\ar\Element;
use prime\models\ar\Permission;
use prime\models\elements\ForBreadcrumb;
use prime\values\ElementId;

class ElementRepository
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
    ) {
    }

    public function retrieveForBreadcrumb(ElementId $id): ForBreadcrumbInterface
    {
        $record = Element::findOne(['id' => $id]);
        return new ForBreadcrumb($record);
    }
}
