<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use prime\components\ActiveQuery;
use prime\models\ar\ResponseForLimesurvey;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\queries\ResponseForLimesurveyQuery;

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
        $this->testRelation('responses', ResponseForLimesurvey::class);
    }

    public function testGetWorkspace(): void
    {
        $this->testRelation('workspace', WorkspaceForLimesurvey::class);
    }
}
