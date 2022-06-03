<?php

declare(strict_types=1);

namespace prime\tests\unit\models\search;

use Codeception\Test\Unit;
use prime\models\search\FacilitySearch;
use prime\models\search\Project;
use prime\models\search\User;
use prime\tests\_helpers\SearchModelTestTrait;
use prime\tests\unit\models\ModelTest;
use yii\base\Model;

/**
 * @covers \prime\models\search\User
 */
class UserSearchTest extends Unit
{
    use SearchModelTestTrait;

    public function validSamples(): iterable
    {
        return [
            [
                [
                    'id' => 15,
                    'name' => 'test',
                    'email' => 'test',
                    'created_at' => '2021',
                ],
            ],
        ];
    }

    public function invalidSamples(): iterable
    {
        return [
            [
                [
                    'id' => 'not a number',
                    // This can be fixed by a typehint
                    'email' => ['not a string'],
                    'name' => ['not a string'],
                ],
            ],
        ];
    }

    private function getModel(): Model
    {
        return new User();
    }
}
