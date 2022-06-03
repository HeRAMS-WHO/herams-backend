<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\element\ElementForBreadcrumbInterface as ForBreadcrumbInterface;
use prime\models\ar\Element;
use prime\models\ar\elements\Svelte;
use prime\models\element\ElementForBreadcrumb;
use prime\models\forms\element\Chart;
use prime\values\ElementId;

class ElementRepository
{
    public function __construct(
        private ModelHydrator $hydrator
    ) {
    }

    public function retrieveForBreadcrumb(ElementId $id): ForBreadcrumbInterface
    {
        $record = Element::findOne([
            'id' => $id,
        ]);
        return new ElementForBreadcrumb($record);
    }

    public function create(Chart $chart): Element
    {
        $element = new Element();
        $this->hydrator->hydrateActiveRecord($chart, $element);
        if (! $element->validate()) {
            throw new \RuntimeException('Failed to create: ' . print_r($element->errors, true));
        }
        $element->save(false);
        return $element;
    }
}
