<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use prime\models\ar\AccessRequest;

/**
 * @covers \prime\models\ar\AccessRequest
 */
class AccessRequestTest extends ActiveRecordTest
{
    public function validSamples(): array
    {
        return [];
    }

    public function invalidSamples(): array
    {
        return [
            [
                [],
            ],
        ];
    }

    public function testModelHasExpirationDate(): void
    {
        $accessRequest = new AccessRequest();
        $this->assertNotNull($accessRequest->expires_at);
    }

    public function testPopulateClearsDefaults(): void
    {
        $accessRequest = new AccessRequest();

        AccessRequest::populateRecord($accessRequest, [
            'id' => 12345,
        ]);
        $this->assertNull($accessRequest->expires_at);
    }
}
