<?php
declare(strict_types=1);

namespace prime\tests\_helpers;

trait ModelTestTrait
{
    use AllAttributesMustHaveLabels, AllFunctionsMustHaveReturnTypes, AttributeValidationByExample, YiiLoadMustBeDisabled;
}
