<?php

declare(strict_types=1);

namespace herams\api\domain\project;

use herams\common\values\ProjectId;

final class UpdateProject extends NewProject
{
    public function __construct(
        public readonly ProjectId $id
    ) {
        parent::__construct();
    }
}
