<?php

declare(strict_types=1);

namespace prime\helpers;

use prime\attributes\SupportedType;
use prime\interfaces\ActiveRecordHydratorInterface;
use prime\interfaces\ValidateTypeInterface;
use prime\models\ActiveRecord;
use prime\models\RequestModel;
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

        foreach ($attributes as $attribute) {
            /** @var SupportedType $source */
            $source = $attribute->newInstance();

            $this->strategies[] = [$source, $hydrator];
        }
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

    public function hydrateRequestModel(ActiveRecord $source, RequestModel $target): void
    {
        /**
         * @var ValidateTypeInterface $validator
         * @var ActiveRecordHydratorInterface $hydrator
         */
        foreach ($this->strategies as [$validator, $hydrator]) {
            // Swap parameters for validation, all hydrators must support 2 way
            if ($validator->validate($target, $source)) {
                $hydrator->hydrateRequestModel($source, $target);
                return;
            }
        }
    }
}
