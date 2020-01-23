<?php
declare(strict_types=1);

namespace prime\helpers;


use prime\interfaces\ColumnDefinition;
use prime\interfaces\HeramsResponseInterface;
use yii\helpers\Inflector;

class GetterColumn implements ColumnDefinition
{
    private $property;
    private $headerText;
    private $headerCode;

    public function __construct(string $property, string $headerText, ?string $headerCode = null)
    {
        $this->property = $property;
        $this->headerText = $headerText;
        $this->headerCode = $headerCode ?? Inflector::camel2id($this->property, '_');

    }

    public function getHeaderCode(): string
    {
        return $this->headerCode;
    }

    public function getHeaderText(): string
    {
        return $this->headerText;
    }

    public function getValue(HeramsResponseInterface $response): ?string
    {
        $method = 'get' . ucfirst($this->property);
        $result = $response->{$method}();
        return isset($result) ? (string) $result : null;
    }
}