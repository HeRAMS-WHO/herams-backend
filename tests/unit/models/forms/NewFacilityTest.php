<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms;

use Codeception\Test\Unit;
use prime\interfaces\WorkspaceForNewOrUpdateFacility;
use prime\models\forms\NewFacility;
use prime\models\forms\UpdateFacility;
use prime\tests\_helpers\AllAttributesMustHaveLabels;
use prime\tests\_helpers\AllFunctionsMustHaveReturnTypes;
use prime\tests\_helpers\AttributeValidationByExample;
use prime\tests\_helpers\ModelTestTrait;
use prime\tests\_helpers\YiiLoadMustBeDisabled;
use prime\values\FacilityId;

/**
 * @covers \prime\models\forms\NewFacility
 */
class NewFacilityTest extends Unit
{
    use AllFunctionsMustHaveReturnTypes;
    use AttributeValidationByExample;
    use YiiLoadMustBeDisabled;

    private function getWorkspace(): WorkspaceForNewOrUpdateFacility
    {
        return $this->getMockBuilder(WorkspaceForNewOrUpdateFacility::class)->getMock();
    }
    private function getModel(): NewFacility
    {
        return new NewFacility($this->getWorkspace());
    }

    public function testGetId(): void
    {
        $id = new FacilityId("1");
        $workspace = $this->getWorkspace();

        $model = new UpdateFacility($id, $workspace);

        // We care about the value, not the instance.
        $this->assertSame($id->getValue(), $model->getId()->getValue());
    }


    public function testGetWorkspace(): void
    {
        $workspace = $this->getWorkspace();

        $model = new NewFacility($workspace);

        $this->assertSame($workspace, $model->getWorkspace());
    }

    public function testTypeHint(): void
    {
        $workspace = $this->getWorkspace();

        $model = new NewFacility($workspace);
    }

    public function validSamples(): iterable
    {
        yield [
            [
                'data' => [
                    'name' => 'cool stuff',
                ]
            ]
        ];
    }

    public function invalidSamples(): iterable
    {
        return [];
//        yield [
//            [
//                'data' => [
//                    'name' => '',
//                ]
//            ]
//        ];
    }
}
