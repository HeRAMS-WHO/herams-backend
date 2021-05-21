<?php
declare(strict_types=1);

namespace prime\models\facility;

use prime\values\FacilityId;
use prime\values\Point;

class FacilityForList implements \prime\interfaces\FacilityForList
{
    private string $name;
    private null|string $alternativeName;
    private null|string $code;
    private null|Point $coords;
    
    public function id(): FacilityId
    {
        return new FacilityId(0);
    }

    public function name(): string
    {
        return '';
    }

    public function alternativeName(): null|string
    {
        return $this->alternativeName;
    }

    public function code(): null|string
    {
        return $this->code;
    }

    public function coords(): null|Point
    {
        return $this->coords;
    }
}
