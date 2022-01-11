<?php

declare(strict_types=1);

namespace prime\components;

class HydratedActiveDataProvider extends FilteredActiveDataProvider
{
    public function __construct(
        private \Closure $modelFactory,
        $config = []
    ) {
        parent::__construct($config);
    }

    protected function prepareModels(): array
    {
        $result = [];
        foreach (parent::prepareModels() as $key => $record) {
            $result[$key] = ($this->modelFactory)($record);
        }
        return $result;
    }

    protected function prepareKeys($models): array
    {
        return array_keys($models);
    }
}
