<?php

declare(strict_types=1);

namespace prime\tests\unit\models\search;

use Codeception\Test\Unit;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\search\Workspace;
use prime\tests\_helpers\SearchModelTestTrait;
use yii\base\Model;

/**
 * @covers \prime\models\search\Workspace
 */
class WorkspaceSearchTest extends Unit
{
    use SearchModelTestTrait;

    public function validSamples(): iterable
    {
        return [
            [
                [
                    'id' => 15,
                    'title' => 'test',
                    'created_at' => '2021',
                    'favorite' => '0'
                ]
            ]
        ];
    }

    public function invalidSamples(): iterable
    {
        return [
            [
                [
                    'id' => 'not a number',
                    // This can be fixed by using a typehint on the property
                    'title' => ['array value'],
                    'favorite' => 'not a boolean',
                ]
            ]
        ];
    }

    private function getModel(): Model
    {
        return new Workspace(new Project(), new User());
    }
}
