<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\ValueOptionInterface;
use herams\common\interfaces\HeramsVariableSetRepositoryInterface;
use herams\common\values\ProjectId;
use yii\base\Action;

class Variables extends Action
{
    public function run(HeramsVariableSetRepositoryInterface $heramsVariableSetRepository, int $id): array
    {
        $variableSet = $heramsVariableSetRepository->retrieveForProject(new ProjectId($id));
        $locale = \Yii::$app->language;
        $result = [];

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
