<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\project;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\ValueOptionInterface;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\values\ProjectId;
use yii\base\Action;
use yii\web\User;

class Variables extends Action
{
    public function run(HeramsVariableSetRepositoryInterface $heramsVariableSetRepository, int $id): array
    {
        $variableSet = $heramsVariableSetRepository->retrieveForProject(new ProjectId($id));

        $locale = \Yii::$app->language;
        $result = [];
        /**
         *
         */
        foreach ($variableSet->getVariables() as $variable) {
            $variableData = [
                'label' => $variable->getTitle($locale),
                'name' => $variable->getName(),
            ];
            if ($variable instanceof ClosedVariableInterface) {
                /** @var ValueOptionInterface $valueOption */
                foreach ($variable->getValueOptions() ?? [] as $valueOption) {
                    $variableData['valueOptions'][$valueOption->getRawValue()] = $valueOption->getDisplayValue($locale);
                }
            }
            $result[] = $variableData;
        }

        return $result;
    }
}
