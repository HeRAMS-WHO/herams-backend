<?php

declare(strict_types=1);

namespace prime\tests\helpers;

use Codeception\Test\Unit;
use prime\models\ar\User;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\values\UserId;
use prime\helpers\ProposedGrant;
use prime\models\ar\WorkspaceForLimesurvey;
use stdClass;

/**
 * @covers \prime\helpers\ProposedGrant
 */
final class ProposedGrantTest extends Unit
{
    public function testGetters(): void
    {
        $source = new stdClass();
        $target = new stdClass();

        $proposedGrant = new ProposedGrant($source, $target, 'Test permission');

        $this->assertSame($source, $proposedGrant->getSource());
        $this->assertSame($target, $proposedGrant->getTarget());
        $this->assertSame('Test permission', $proposedGrant->getPermission());
    }
}
