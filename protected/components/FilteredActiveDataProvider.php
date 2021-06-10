<?php


namespace prime\components;

use Closure;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\db\QueryInterface;
use function iter\filter;
use function iter\slice;
use function iter\toArray;

class FilteredActiveDataProvider extends ActiveDataProvider
{
    public Closure $filter;
    public Closure $totalCount;

    protected function prepareModels()
    {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
        }
        /** @var QueryInterface|Query $query */
        $query = clone $this->query;
        if (($sort = $this->getSort()) !== false) {
            try {
                $query->addOrderBy($sort->getOrders());
            } catch (\Throwable $t) {
                throw $t;
            }
        }

        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();
            \Yii::beginProfile('pagination');
            $filtered = $this->filter($query->each(100, $this->db));
            $result = toArray(slice($filtered, $pagination->getOffset(), $pagination->getLimit()));
            \Yii::endProfile('pagination');
            return $result;
        }

        return toArray($this->filter($query->each(100, $this->db)));
    }

    protected function filter(iterable $iterable): iterable
    {
        return isset($this->filter) ? filter($this->filter, $iterable) : $iterable;
    }

    protected function prepareTotalCount()
    {
        $query = clone $this->query;
        if (isset($this->totalCount)) {
            return ($this->totalCount)($query);
        }
        if ($this->query instanceof Query) {
            $base = $query->each(1000, $this->db);
        } else {
            $base = $this->query->all($this->db);
        }
        return \iter\count($this->filter($base));
    }
}
