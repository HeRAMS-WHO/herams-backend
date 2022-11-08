<?php

declare(strict_types=1);

namespace herams\common\domain\project;

use herams\common\values\ProjectId;
use prime\models\project\ProjectLocales;

interface ProjectLocalesRetriever
{
    public function retrieveProjectLocales(ProjectId $id): ProjectLocales;
}
