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

/**
 * @covers \prime\helpers\ProposedGrant
 */
final class ProposedGrantTest extends Unit
{
    public function testGetters(): void
    {
        $email = 'test@test.com';
        $id = 12345;
        $name = 'Test user';

        $user = new User();
        $user->email = $email;
        $user->id = $id;
        $user->name = $name;


        $permission = new Permission();
        $permission->source_id = "1";
        $permission->source = 'source';

        $label = 'Project label';
        $projectId = 23456;
        $workspaceId = 12345;

        $workspace = new WorkspaceForLimesurvey();
        $workspace->id = $workspaceId;
        $workspace->title = $label;
        $workspace->project_id = $projectId;


        $proposedGrant = new ProposedGrant($user, $workspace, 'Test permission');

        $this->assertSame($user, $proposedGrant->getSource());
        $this->assertSame($workspace, $proposedGrant->getTarget());
        $this->assertSame('Test permission', $proposedGrant->getPermission());
    }
}
