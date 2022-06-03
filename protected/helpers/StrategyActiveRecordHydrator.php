<?php

declare(strict_types=1);

namespace prime\helpers;

use prime\attributes\SupportedType;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\interfaces\ValidateTypeInterface;
use prime\models\ActiveRecord;
use yii\base\Model;

class StrategyActiveRecordHydrator implements ActiveRecordHydratorInterface
{
    /**
     * @var list<array{0: ValidateTypeInterface, 1: ActiveRecordHydratorInterface}>
     */
    private array $strategies = [];

    public function registerAttributeStrategy(ActiveRecordHydratorInterface $hydrator): void
    {
        $reflection = new \ReflectionClass($hydrator);
        $attributes = $reflection->getAttributes(SupportedType::class);
        if (empty($attributes)) {
            throw new \InvalidArgumentException("Hydrator is missing source attribute, add it or use manual registration");
        }
        /** @var SupportedType $source */
        $source = $attributes[0]->newInstance();

        $this->strategies[] = [$source, $hydrator];
    }

    public function hydrateActiveRecord(Model $source, ActiveRecord $target): void
    {
        /**
         * @var ValidateTypeInterface $validator
         * @var ActiveRecordHydratorInterface $hydrator
         */
        foreach ($this->strategies as [$validator, $hydrator]) {
            if ($validator->validate($source, $target)) {
                $hydrator->hydrateActiveRecord($source, $target);
                return;
            }
        }

        throw new \RuntimeException("No strategy found for model of class " . get_class($source));
    }
}
