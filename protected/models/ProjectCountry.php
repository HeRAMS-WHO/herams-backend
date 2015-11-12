<?php

namespace prime\models;

use prime\components\ActiveRecord;

class ProjectCountry extends ActiveRecord
{
    public function getCountry()
    {
        return Country::findOne($this->country_iso_3);
    }
}