<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use herams\common\domain\user\User;
use herams\common\models\Permission;
use herams\common\models\Project;

/**
 * @covers \herams\common\domain\permission\PermissionRepository
 */
class PermissionRepositoryTest extends Unit
{
    private function createPermission(): Permission
    {
        $project = new Project();
        $project->title = 'Test project';
        $project->base_survey_eid = 12345;
        $project->save();

        $permission = new Permission([
            'source' => User::class,
            'source_id' => TEST_USER_ID,
            'target' => get_class($project),
            'target_id' => $project->id,
            'permission' => Permission::PERMISSION_READ,
        ]);
        $permission->save();

        return $permission;
    }


}
