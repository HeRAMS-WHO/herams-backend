<?php

namespace prime\models\ar;

use prime\components\ActiveRecord;
use prime\models\Country;

class ProjectCountry extends ActiveRecord
{
    public function getCountry()
    {
        return Country::findOne($this->country_iso_3);
    }
}