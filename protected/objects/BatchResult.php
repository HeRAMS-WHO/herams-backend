<?php


namespace prime\objects;


class BatchResult
{
    private $successCount = 0;
    private $failCount = 0;

    public function __construct(int $success, int $fail)
    {
        $this->successCount = $success;
        $this->failCount = $fail;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getFailCount(): int
    {
        return $this->failCount;
    }
}