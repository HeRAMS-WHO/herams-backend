<?php

declare(strict_types=1);

namespace herams\common\interfaces;

use herams\common\values\SurveyId;

interface RecordInterface extends \Collecthor\DataInterfaces\RecordInterface
{
    public function getSurveyId(): SurveyId;
}
