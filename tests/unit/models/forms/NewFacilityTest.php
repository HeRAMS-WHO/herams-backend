<?php
declare(strict_types=1);

namespace prime\tests\unit\models\forms;

use Codeception\Test\Unit;
use prime\interfaces\WorkspaceForNewOrUpdateFacility;
use prime\models\forms\NewFacility;
use prime\models\forms\UpdateFacility;
use prime\tests\_helpers\ModelTestTrait;
use prime\values\FacilityId;

/**
 * @covers \prime\models\forms\NewFacility
 */
class NewFacilityTest extends Unit
{
    use ModelTestTrait;

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
                'name' => 'cool stuff',
                'alternative_name' => 'test',
                'code' => 'code',
                'coordinates' => '(14, 5)'
            ]
        ];

        yield [
            [
                'name' => 'cool stuff'
            ]
        ];
    }

    public function invalidSamples(): iterable
    {
        yield [
            [
                'coordinates' => 'wrong',
                'alternative_name' => 'ac',
                'code' => 'ab'
            ]
        ];
    }
}
