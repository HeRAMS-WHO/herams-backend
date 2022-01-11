<?php
declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\repositories\PermissionRepository;
use yii\base\InvalidArgumentException;

/**
 * @covers \prime\repositories\PermissionRepository
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

    public function test()
    {

        $respository = new PermissionRepository();

        $this->assertEquals(null, $respository->retrieve(10));
        $this->expectException(InvalidArgumentException::class);
        $respository->retrieveOrThrow(10);

        $permission = $this->createPermission();
        $this->assertObjectEquals($permission, $respository->retrieveOrThrow($permission->id));
    }
}
