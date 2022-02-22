<?php

declare(strict_types=1);

namespace prime\tests\unit\models\search;

use Codeception\Test\Unit;
use prime\models\search\FacilitySearch;
use prime\tests\_helpers\SearchModelTestTrait;
use prime\tests\unit\models\ModelTest;
use yii\base\Model;

/**
 * @covers \prime\models\search\FacilitySearch
 */
class FacilitySearchTest extends Unit
{
    use SearchModelTestTrait;

    public function validSamples(): iterable
    {
        return [
            [
                [
                    'id' => "15",
                    'name' => 'test123'
                ],
            ],

        ];
    }

    public function invalidSamples(): iterable
    {
        return [
            [
                [
                    'id' => "cool",
                    'name' => 'test123'
                ],
            ]
        ];
    }

    protected function getModel(): Model
    {
        return new FacilitySearch();
    }
}
