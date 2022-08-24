<?php

declare(strict_types=1);

namespace prime\objects\enums;

use JetBrains\PhpStorm\Internal\TentativeType;
use function iter\map;
use function iter\toArrayWithKeys;

enum ProjectStatus:int implements \JsonSerializable
{
    case Ongoing = 0;
    case Baseline = 1;
    case Target = 2;
    case Emergency = 3;

    public function label(): string
    {
        return match ($this) {
            self::Ongoing => \Yii::t('app', 'Ongoing'),
            self::Baseline => \Yii::t('app', 'Baseline'),
            self::Target => \Yii::t('app', 'Target'),
            self::Emergency => \Yii::t('app', 'Emergency specific')
        };
    }

    public static function toArray(): array
    {
        $result = [];
        foreach (self::cases() as $projectStatus) {
            $result[$projectStatus->value] = $projectStatus->label();
        }
        return $result;
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}
