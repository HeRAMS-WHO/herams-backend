<?php

declare(strict_types=1);

namespace prime\tests\unit\models\response;

use Carbon\Carbon;
use Codeception\Test\Unit;
use prime\interfaces\HeramsResponseInterface;
use prime\models\response\ResponseForList;
use prime\objects\enums\FacilityAccessibility;
use prime\objects\enums\FacilityCondition;
use prime\objects\enums\FacilityFunctionality;

/**
 * @covers \prime\models\response\ResponseForList
 */
class ResponseForListTest extends Unit
{
    private function getHeramsResponse()
    {
        return new class implements HeramsResponseInterface {
            public string $condition = '';
            public string $accessibility = '';
            public string $functionality = '';

            public function getLatitude(): ?float
            {
            }

            public function getLongitude(): ?float
            {
            }

            public function getId(): int
            {
                return 12345;
            }

            public function getAutoIncrementId(): int
            {
                return 54321;
            }

            public function getType(): ?string
            {
            }

            public function getName(): ?string
            {
            }

            public function getValueForCode(string $code)
            {
            }

            public function getSubjectId(): string
            {
            }

            public function getLocation(): ?string
            {
            }

            public function getDate(): ?Carbon
            {
                return Carbon::createFromTimestampUTC(123456);
            }

            public function getSubjects(): iterable
            {
            }

            public function getSubjectAvailability(): float
            {
            }

            public function getSubjectAvailabilityBucket(): int
            {
            }

            public function getFunctionality(): string
            {
                return $this->functionality;
            }

            public function getAccessibility(): string
            {
                return (FacilityAccessibility::tryFrom($this->accessibility) ?? FacilityAccessibility::Unknown)->value;
            }

            public function getCondition(): string
            {
                return (FacilityCondition::tryFrom($this->condition) ?? FacilityCondition::Unknown)->value;
            }

            public function getMainReason(): ?string
            {
                // TODO: Implement getMainReason() method.
            }

            public function getRawData(): array
            {
                // TODO: Implement getRawData() method.
            }
        };
    }

    public function testGetId(): void
    {

        $model = new ResponseForList($this->getHeramsResponse());

        $this->assertSame(54321, $model->getId()->getValue());
    }

    public function testGetExternalId(): void
    {
        $model = new ResponseForList($this->getHeramsResponse());
        $this->assertSame(12345, $model->getExternalId());
    }

    public function testGetCondition(): void
    {
        $response = $this->getHeramsResponse();
        $model = new ResponseForList($response);

        $this->assertSame(FacilityCondition::Unknown, $model->getCondition());
        $response->condition = 'badvalue';
        $this->assertSame(FacilityCondition::Unknown, $model->getCondition());
        foreach (FacilityCondition::cases() as $case) {
            $response->condition = $case->value;
            $this->assertSame($case->value, $model->getCondition()->value);
        }
    }

    public function testGetAccessibility(): void
    {
        $response = $this->getHeramsResponse();
        $model = new ResponseForList($response);

        $this->assertSame(FacilityAccessibility::Unknown, $model->getAccessibility());
        $response->accessibility = 'badvalue';
        $this->assertSame(FacilityAccessibility::Unknown, $model->getAccessibility());
        foreach (FacilityAccessibility::cases() as $case) {
            $response->accessibility = $case->value;
            $this->assertSame($case->value, $model->getAccessibility()->value);
        }
    }

    public function testGetFunctionality(): void
    {
        $response = $this->getHeramsResponse();
        $model = new ResponseForList($response);

        $this->assertSame(FacilityFunctionality::Unknown, $model->getFunctionality());
        $response->functionality = 'badvalue';
        $this->assertSame(FacilityFunctionality::Unknown, $model->getFunctionality());
        foreach (FacilityFunctionality::cases() as $case) {
            $response->functionality = $case->value;
            $this->assertSame($case->value, $model->getFunctionality()->value);
        }
    }

    public function testGetDateOfUpdate(): void
    {
        $response = $this->getHeramsResponse();
        $model = new ResponseForList($response);

        $this->assertSame(123456, $model->getDateOfUpdate()->getTimestamp());
    }
}
