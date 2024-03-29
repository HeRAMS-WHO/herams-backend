<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use herams\common\domain\user\User;

/**
 * @covers \herams\common\domain\favorite\Favorite
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
