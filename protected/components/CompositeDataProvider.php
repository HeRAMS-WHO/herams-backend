<?php

declare(strict_types=1);

namespace prime\components;

use prime\helpers\ArrayHelper;
use yii\data\BaseDataProvider;
use yii\data\DataProviderInterface;

use function iter\chain;
use function iter\map;
use function iter\toArray;

class CompositeDataProvider extends BaseDataProvider
{
    private $_sort = null;

    public function __construct(
        private DataProviderInterface $a,
        private DataProviderInterface $b,
        array $config = []
    ) {
        if ($a->getPagination() !== false || $b->getPagination() !== false) {
            throw new \InvalidArgumentException('Passed data providers should have pagination disabled');
        }
        parent::__construct($config);
    }

    public function getSort()
    {
        return parent::getSort() ?: $this->a->getSort();
    }

    public function prepare($forcePrepare = false): void
    {
        $this->a->prepare($forcePrepare);
        $this->b->prepare($forcePrepare);
        parent::prepare($forcePrepare);
    }

    protected function prepareModels(): array
    {
        $pagination = $this->getPagination();
        if ($pagination !== false) {
            $pagination->totalCount = $this->getTotalCount();
        }


        $models = array_merge($this->a->getModels(), $this->b->getModels());

        // Sort
        $sort = $this->getSort();
        if ($sort !== false) {
            $orders = $sort->getOrders();
            if (!empty($orders)) {
                ArrayHelper::multisort($models, array_keys($orders), array_values($orders), $sort->sortFlags);
            }
        }

        // Slice
        if ($pagination->getPageSize() > 0) {
            $models = array_slice($models, $pagination->getOffset(), $pagination->getLimit(), true);
        }
        return $models;
    }

    protected function prepareKeys($models): array
    {
        return toArray(chain(
            map(fn(string $key) => "A:$key", $this->a->getKeys()),
            map(fn(string $key) => "B:$key", $this->b->getKeys()),
        ));
    }

    protected function prepareTotalCount(): int
    {
        return $this->a->getTotalCount() + $this->b->getTotalCount();
    }
}
