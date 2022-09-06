<?php

declare(strict_types=1);

namespace prime\interfaces\project;

use prime\models\project\ProjectLocales;
use prime\values\ProjectId;

interface ProjectLocalesRetriever
{
    public function retrieveProjectLocales(ProjectId $id): ProjectLocales;
}
