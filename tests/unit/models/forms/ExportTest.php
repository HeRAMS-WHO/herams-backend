<?php
declare(strict_types=1);

namespace prime\tests\unit\models\forms;

use prime\models\forms\Export;
use prime\tests\_helpers\Survey;
use prime\tests\unit\models\ModelTest;
use yii\base\Model;

/**
 * @covers \prime\models\forms\Export
 */
class ExportTest extends ModelTest
{
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
