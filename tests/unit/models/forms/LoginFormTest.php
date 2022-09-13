<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms;

use Codeception\Test\Unit;
use prime\models\forms\LoginForm;
use prime\models\forms\NewFacility;
use prime\models\forms\UpdateFacility;
use prime\tests\_helpers\ModelTestTrait;
use prime\values\FacilityId;

/**
 * @covers \prime\models\forms\LoginForm
 */
class LoginFormTest extends Unit
{
    use ModelTestTrait;

    private function getModel(): LoginForm
    {
        return new LoginForm();
    }

//    public function testGetId(): void
//    {
//        $id = new FacilityId("1");
//        $workspace = $this->getWorkspace();
//
//        $model = new UpdateFacility($id, $workspace);
//
//        // We care about the value, not the instance.
//        $this->assertSame($id->getValue(), $model->getId()->getValue());
//    }
//
//
//    public function testGetWorkspace(): void
//    {
//        $workspace = $this->getWorkspace();
//
//        $model = new NewFacility($workspace);
//
//        $this->assertSame($workspace, $model->getWorkspace());
//    }
//
//    public function testTypeHint(): void
//    {
//        $workspace = $this->getWorkspace();
//
//        $model = new NewFacility($workspace);
//    }

    public function validSamples(): iterable
    {
//        yield [
//            [
//                'login' => 'cool stuff',
//                'password' => 'test',
//            ]
//        ];
        return [];
    }

    public function invalidSamples(): iterable
    {
        yield [
            [
                'login' => 'cool stuff',
                'password' => 'test',

            ],
        ];
    }
}
