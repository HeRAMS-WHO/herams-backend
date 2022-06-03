<?php

declare(strict_types=1);

namespace prime\tests\unit\models\ar;

use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\jobs\accessRequests\CreatedNotificationJob;
use prime\models\ar\AccessRequest;
use prime\models\ar\Project;
use prime\tests\FunctionalTester;

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
