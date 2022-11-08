<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use herams\common\domain\user\User;
use herams\common\domain\user\UserRepository;
use herams\common\queries\ActiveQuery;
use yii\base\InvalidArgumentException;

/**
 * @covers \herams\common\domain\user\UserRepository
 */
class UserRepositoryTest extends Unit
{
    private function createRepository(): UserRepository
    {
        return new UserRepository();
    }

    public function testFind(): void
    {
        $repository = $this->createRepository();
        $query = $repository->find();

        $this->assertInstanceOf(ActiveQuery::class, $query);
        $this->assertEquals(User::class, $query->modelClass);
    }

    public function testRetrieve(): void
    {
        $repository = $this->createRepository();

        $this->assertEquals(User::findOne([
            'id' => TEST_USER_ID,
        ]), $repository->retrieve(TEST_USER_ID));
    }

    public function testRetrieveOrThrowSuccess(): void
    {
        $repository = $this->createRepository();

        $this->assertEquals(User::findOne([
            'id' => TEST_USER_ID,
        ]), $repository->retrieveOrThrow(TEST_USER_ID));
    }

    public function testRetrieveOrThrowFailed(): void
    {
        $repository = $this->createRepository();

        $this->expectException(InvalidArgumentException::class);
        $repository->retrieveOrThrow(12345);
    }
}
