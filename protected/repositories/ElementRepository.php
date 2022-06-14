<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\helpers\ModelHydrator;
use prime\interfaces\element\ElementForBreadcrumbInterface as ForBreadcrumbInterface;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\models\ar\Element;
use prime\models\ar\elements\Svelte;
use prime\models\ar\RawElement;
use prime\models\element\ElementForBreadcrumb;
use prime\models\forms\element\Chart;
use prime\models\forms\element\SvelteElement;
use prime\values\ElementId;
use yii\web\NotFoundHttpException;

class ElementRepository
{
    public function __construct(
        private ModelHydrator $hydrator,
        private HeramsVariableSetRepositoryInterface $variableSetRepository
    ) {
    }

    public function retrieveForBreadcrumb(ElementId $id): ForBreadcrumbInterface
    {
        $record = Element::findOne([
            'id' => $id,
        ]);
        return new ElementForBreadcrumb($record);
    }

    public function create(SvelteElement $config): ElementId
    {
        $element = new RawElement();
        $this->hydrator->hydrateActiveRecord($config, $element);
        if (! $element->validate()) {
            throw new \RuntimeException('Failed to create: ' . print_r($element->errors, true));
        }
        $element->save(false);
        return new ElementId($element->id);
    }

    public function retrieveForUpdate(ElementId $elementId): SvelteElement
    {
        $element = RawElement::findOne(['id' => $elementId->getValue()]);
        if (!isset($element)) {
            throw new NotFoundHttpException();
        }

        $result = new SvelteElement($this->variableSetRepository);
        $this->hydrator->hydrateFromActiveRecord($element, $result);
        return $result;
    }
}
