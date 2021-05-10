<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\models\ActiveRecord;

class FacilityResponse extends ActiveRecord
{
    public function getLatitude(): ?float
    {
        if (isset($this->extracted_location)) {
            return unpack('L/c/L/dlat/dlng', $this->extracted_location)['lat'];
        }
        return null;
    }

    public function getLongitude(): ?float
    {
        if (isset($this->extracted_location)) {
            return unpack('L/c/L/dlat/dlng', $this->extracted_location)['lng'];
        }
        return null;
    }
}
