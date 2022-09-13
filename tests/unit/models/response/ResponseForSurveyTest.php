<?php

declare(strict_types=1);

namespace prime\tests\unit\models\response;

use Codeception\Test\Unit;
use prime\models\response\ResponseForSurvey;
use prime\values\ResponseId;

/**
 * @covers \prime\models\response\ResponseForSurvey
 */
class ResponseForSurveyTest extends Unit
{
    public function testGetId(): void
    {
        $responseId = new ResponseId(15);
        $model = new ResponseForSurvey($responseId, null, null, 'a');
        $this->assertSame($responseId->getValue(), $model->getId()->getValue());
    }

    public function testGetExternalId(): void
    {
        $responseId = new ResponseId(15);
        $model = new ResponseForSurvey($responseId, 456, 123, 'a');
        $this->assertSame(123, $model->getExternalResponseId()->getResponseId());
        $this->assertSame(456, $model->getExternalResponseId()->getSurveyId());
    }
}
