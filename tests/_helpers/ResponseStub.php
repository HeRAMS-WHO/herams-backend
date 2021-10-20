<?php

declare(strict_types=1);

namespace prime\tests\_helpers;

use SamIT\LimeSurvey\Interfaces\ResponseInterface;

class ResponseStub implements ResponseInterface
{
    private array $data;

    public function __construct(
        private int $surveyId,
        private string $id,
        private \DateTimeImmutable $submitDate,
    ) {
        $this->data = [
            'id' => $id,
            'UOID' => 'test',
            'Update' => '1900-01-01 10:00:00'
        ];
        $desiredLength = mt_rand(0, 50);
        while (count($this->data) < $desiredLength) {
            $this->data[base64_encode(random_bytes(10))] = random_int(1, 2500);
        }
    }

    public function getSurveyId(): int
    {
        return $this->surveyId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSubmitDate(): \DateTimeInterface
    {
        return $this->submitDate;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
