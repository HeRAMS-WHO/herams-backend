<?php

declare(strict_types=1);

namespace prime\models\forms\dashboarding;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use yii\base\Model;

class Chart extends Model
{
    public int|string $width;
    public int|string $height;
    public function __construct(
        private VariableSetInterface $variables
    ) {
        parent::__construct([]);
    }

    public function questionOptions(): iterable
    {
        foreach ($this->variables->getVariables() as $variable) {
            if ($variable instanceof ClosedVariableInterface) {
                yield $variable->getName() => $variable->getTitle();
            }
        }
    }
}
