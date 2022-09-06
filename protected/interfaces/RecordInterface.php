<?php

declare(strict_types=1);

namespace prime\interfaces;

use prime\values\SurveyId;

interface RecordInterface extends \Collecthor\DataInterfaces\RecordInterface
{
    public function getSurveyId(): SurveyId;
}
