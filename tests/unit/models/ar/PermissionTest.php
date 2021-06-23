<?php
declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use prime\models\ar\Permission;
use yii\base\BaseObject;

/**
 * @covers \prime\models\ar\Permission
 */
class PermissionTest extends ActiveRecordTest
{

    public function invalidSamples(): array
    {
        return [];
    }

    public function validSamples(): array
    {
        return [];
    }

    public function testGetSourceAuthorizable(): void
    {
        $permission = new Permission();
        $permission->source_id = "1";
        $permission->source = 'source';
        $this->assertSame($permission->source_id, $permission->sourceAuthorizable()->getId());
        $this->assertSame($permission->source, $permission->sourceAuthorizable()->getAuthName());
    }

    public function testGetTargetAuthorizable(): void
    {
        $permission = new Permission();
        $permission->target_id = "1";
        $permission->target = 'target';
        $this->assertSame($permission->target_id, $permission->targetAuthorizable()->getId());
        $this->assertSame($permission->target, $permission->targetAuthorizable()->getAuthName());
    }

    public function testGetGrant(): void
    {
        $permission = new Permission();
        $permission->source_id = "1";
        $permission->source = 'source';
        $permission->target_id = "2";
        $permission->target = 'target';
        $permission->permission = 'permmm';

        $this->assertSame($permission->source_id, $permission->getGrant()->getSource()->getId());
        $this->assertSame($permission->source, $permission->getGrant()->getSource()->getAuthName());
        $this->assertSame($permission->target_id, $permission->getGrant()->getTarget()->getId());
        $this->assertSame($permission->target, $permission->getGrant()->getTarget()->getAuthName());

        $this->assertSame($permission->permission, $permission->getGrant()->getPermission());
    }

    public function testPermissionLabels(): void
    {
        $this->assertNotEmpty(Permission::permissionLabels());
    }
}
