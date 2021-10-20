<?php

declare(strict_types=1);

namespace prime\tests\unit\models\response;

use Carbon\Carbon;
use Codeception\Test\Unit;
use prime\interfaces\HeramsResponseInterface;
use prime\models\response\ResponseForList;
use prime\models\response\ResponseForSurvey;
use prime\objects\enums\FacilityAccessibility;
use prime\objects\enums\FacilityCondition;
use prime\objects\enums\FacilityFunctionality;
use prime\values\IntegerId;
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

    public function testUsesLimesurvey(): void
    {
        $responseId = new ResponseId(15);
        $this->assertFalse((new ResponseForSurvey($responseId, null, null, 'a'))->usesLimeSurvey());
        $this->assertFalse((new ResponseForSurvey($responseId, 123, null, 'a'))->usesLimeSurvey());
        $this->assertFalse((new ResponseForSurvey($responseId, null, 123, 'a'))->usesLimeSurvey());
        $this->assertTrue((new ResponseForSurvey($responseId, 123, 123, 'a'))->usesLimeSurvey());
    }
}
