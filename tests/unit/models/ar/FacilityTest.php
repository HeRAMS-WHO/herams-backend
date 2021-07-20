<?php
declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use prime\components\ActiveQuery;
use prime\models\ar\Response;
use prime\models\ar\Workspace;
use prime\queries\ResponseQuery;

/**
 * @covers \prime\models\ar\Facility
 */
class FacilityTest extends ActiveRecordTest
{


    public function validSamples(): iterable
    {
        return [];
    }

    public function invalidSamples(): iterable
    {
        return [];
    }


    public function testGetResponses(): void
    {
        $this->testRelation('responses', Response::class);
    }

    public function testGetWorkspace(): void
    {
        $this->testRelation('workspace', Workspace::class);
    }
}
