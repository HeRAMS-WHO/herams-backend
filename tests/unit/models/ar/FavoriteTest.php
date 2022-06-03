<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use prime\components\ActiveQuery;
use prime\models\ar\ResponseForLimesurvey;
use prime\models\ar\User;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\queries\ResponseForLimesurveyQuery;

/**
 * @covers \prime\models\ar\Favorite
 */
class FavoriteTest extends ActiveRecordTest
{
    public function validSamples(): iterable
    {
        return [];
    }

    public function invalidSamples(): iterable
    {
        return [];
    }

    public function testRelations(): void
    {
        $this->testRelation('user', User::class);
    }

    public function testGetWorkspaceTarget(): void
    {
        $this->markTestIncomplete();
    }
}
