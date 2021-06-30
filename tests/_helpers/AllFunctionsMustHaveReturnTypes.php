<?php
declare(strict_types=1);

namespace prime\tests\_helpers;

use ReflectionMethod;
use yii\base\Model;

trait AllFunctionsMustHaveReturnTypes
{
    abstract private function getModel(): object;

    /**
     * @coversNothing
     */
    public function testAllFunctionHaveReturnTypes(): void
    {
        $model = $this->getModel();
        $class = new \ReflectionClass($model);
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isConstructor()) {
                continue;
            }

            if ($method->getDeclaringClass()->getName() === $class->getName()) {
                $this->assertNotNull(
                    $method->getReturnType(),
                    "Method {$class->getName()}::{$method->getName()} does not have a return type"
                );
            }
        }
    }
}
