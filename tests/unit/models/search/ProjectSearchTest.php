<?php

declare(strict_types=1);

namespace prime\tests\unit\models\search;

use Codeception\Test\Unit;
use prime\models\search\FacilitySearch;
use prime\models\search\Project;
use prime\tests\_helpers\SearchModelTestTrait;
use prime\tests\unit\models\ModelTest;
use yii\base\Model;

/**
 * @covers \prime\models\search\Project
 */
class ProjectSearchTest extends Unit
{
    use SearchModelTestTrait;

    public function validSamples(): iterable
    {
        return [
            [
                [
                    'id' => 15,
                    'title' => 'test'
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
                    'title' => ['array value']
                ]
            ]
        ];
    }

    private function getModel(): Model
    {
        return new Project();
    }
}
