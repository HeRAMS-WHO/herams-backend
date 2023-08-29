<?php

declare(strict_types=1);

namespace herams\common\traits;

trait JsonBase64EncoderTrait
{
    public function toJson(): string
    {
        // Use Yii2's built-in `toArray` method
        $data = $this->toArray();
        return json_encode($data);
    }

    public function fromJson(string $json)
    {
        return json_decode($json, true); // Return as associative array
    }

    public function toBase64(): string
    {
        return base64_encode($this->toJson());
    }

    public function fromBase64(string $base64): array
    {
        $json = base64_decode($base64);
        return $this->fromJson($json);
    }
}
