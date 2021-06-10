<?php
declare(strict_types=1);

namespace prime\tests\unit\models\forms;

use Codeception\Test\Unit;
use prime\models\forms\Export;
use prime\tests\_helpers\AllAttributesMustHaveLabels;
use prime\tests\_helpers\AllFunctionsMustHaveReturnTypes;
use prime\tests\_helpers\AttributeValidationByExample;
use prime\tests\_helpers\Survey;
use yii\base\Model;

/**
 * @covers \prime\models\forms\Export
 */
class ExportTest extends Unit
{
    use AllAttributesMustHaveLabels, AllFunctionsMustHaveReturnTypes, AttributeValidationByExample;

    public function validSamples(): array
    {
        return [];
    }

    public function invalidSamples(): array
    {
        return [];
    }

    protected function getModel(): Model
    {
        return new Export(new Survey());
    }
}
