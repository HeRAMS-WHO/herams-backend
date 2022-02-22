<?php

declare(strict_types=1);

namespace prime\tests\_helpers;

use yii\base\Model;

trait SearchModelTestTrait
{
//    use AllAttributesMustHaveLabels;
    use AllFunctionsMustHaveReturnTypes;
    use AttributeValidationByExample;

//    use YiiLoadMustBeDisabled;

    abstract private function getModel(): Model;
}
