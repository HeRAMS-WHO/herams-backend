<?php
declare(strict_types=1);

namespace prime\tests\unit\models\search;

use Codeception\Test\Unit;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\models\search\FacilitySearch;
use prime\models\search\Project;
use prime\models\search\Response;
use prime\tests\_helpers\SearchModelTestTrait;
use prime\tests\unit\models\ModelTest;
use yii\base\Model;

/**
 * @covers \prime\models\search\Response
 */
class ResponseSearchTest extends Unit
{
    use SearchModelTestTrait;

    public function validSamples(): iterable
    {
        return [
            [
                [
                    'id' => 15,
                    'hf_id' => 'test',
                    'date' => '2021',
                    'last_updated' => '2021'
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
                ]
            ]
        ];
    }

    private function getModel(): Model
    {
        return new Response(new WorkspaceForLimesurvey());
    }
}
