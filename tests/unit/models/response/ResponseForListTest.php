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
                // TODO: Implement getLatitude() method.
            }

            public function getLongitude(): ?float
            {
                // TODO: Implement getLongitude() method.
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
                // TODO: Implement getType() method.
            }

            public function getName(): ?string
            {
                // TODO: Implement getName() method.
            }

            public function getValueForCode(string $code)
            {
                // TODO: Implement getValueForCode() method.
            }

            public function getSubjectId(): string
            {
                // TODO: Implement getSubjectId() method.
            }

            public function getLocation(): ?string
            {
                // TODO: Implement getLocation() method.
            }

            public function getDate(): ?Carbon
            {
                return Carbon::createFromTimestampUTC(123456);
                // TODO: Implement getDate() method.
            }

            public function getSubjects(): iterable
            {
                // TODO: Implement getSubjects() method.
            }

            public function getSubjectAvailability(): float
            {
                // TODO: Implement getSubjectAvailability() method.
            }

            public function getSubjectAvailabilityBucket(): int
            {
                // TODO: Implement getSubjectAvailabilityBucket() method.
            }

            public function getFunctionality(): string
            {
                return $this->functionality;
            }

            public function getAccessibility(): string
            {
                return $this->accessibility;
            }

            public function getCondition(): string
            {
                return $this->condition;
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

        $this->assertSame(FacilityCondition::unknown(), $model->getCondition());
        $response->condition = 'badvalue';
        $this->assertSame(FacilityCondition::unknown(), $model->getCondition());
        foreach (FacilityCondition::toValues() as $value) {
            $response->condition = $value;
            $this->assertSame($value, $model->getCondition()->value);
        }
    }

    public function testGetAccessibility(): void
    {
        $response = $this->getHeramsResponse();
        $model = new ResponseForList($response);

        $this->assertSame(FacilityAccessibility::unknown(), $model->getAccessibility());
        $response->accessibility = 'badvalue';
        $this->assertSame(FacilityAccessibility::unknown(), $model->getAccessibility());
        foreach (FacilityAccessibility::toValues() as $value) {
            $response->accessibility = $value;
            $this->assertSame($value, $model->getAccessibility()->value);
        }
    }

    public function testGetFunctionality(): void
    {
        $response = $this->getHeramsResponse();
        $model = new ResponseForList($response);

        $this->assertSame(FacilityFunctionality::unknown(), $model->getFunctionality());
        $response->functionality = 'badvalue';
        $this->assertSame(FacilityFunctionality::unknown(), $model->getFunctionality());
        foreach (FacilityFunctionality::toValues() as $value) {
            $response->functionality = $value;
            $this->assertSame($value, $model->getFunctionality()->value);
        }
    }

    public function testGetDateOfUpdate(): void
    {
        $response = $this->getHeramsResponse();
        $model = new ResponseForList($response);

        $this->assertSame(123456, $model->getDateOfUpdate()->getTimestamp());
    }
}
