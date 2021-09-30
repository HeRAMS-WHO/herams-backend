<?php
declare(strict_types=1);

namespace prime\tests\unit\models\search;

use Codeception\Test\Unit;
use prime\models\search\SurveySearch;
use prime\tests\_helpers\SearchModelTestTrait;
use yii\base\Model;

/**
 * @covers \prime\models\search\SurveySearch
 */
class SurveySearchTest extends Unit
{
    use SearchModelTestTrait;

    public function validSamples(): iterable
    {
        return [
            [
                [
                    'id' => "15",
                    'title' => 'test123'
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
                    'title' => 'test123'
                ],
            ]
        ];
    }

    protected function getModel(): Model
    {
        return new SurveySearch();
    }
}
