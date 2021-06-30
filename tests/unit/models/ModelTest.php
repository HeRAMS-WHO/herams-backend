<?php
declare(strict_types=1);

namespace prime\tests\unit\models;

use Codeception\Test\Unit;
use prime\tests\_helpers\AllAttributesMustHaveLabels;
use prime\tests\_helpers\AllRulesMustUseValidAttributes;
use prime\tests\_helpers\AttributeValidationByExample;
use prime\tests\_helpers\ModelTestTrait;
use prime\tests\_helpers\RulesReturnTypeHint;
use yii\base\Model;

/**
 * @coversNothing
 */
abstract class ModelTest extends Unit
{
    use AllAttributesMustHaveLabels;
    use AttributeValidationByExample;
    use AllRulesMustUseValidAttributes;
    /**
     * @var \UnitTester
     */
    protected $tester;

    abstract protected function getModel(): Model;



    public function testGetModel()
    {
        $model = $this->getModel();
        $this->assertInstanceOf(Model::class, $model);
    }
}
