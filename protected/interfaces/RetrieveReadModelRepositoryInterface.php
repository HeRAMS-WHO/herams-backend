<?php
declare(strict_types=1);

namespace prime\interfaces;

use prime\values\IntegerId;
use yii\db\ActiveRecord;

interface RetrieveReadModelRepositoryInterface
{
    /**
     * TODO: Tighten return type
     */
    public function retrieveForRead(IntegerId $id): ActiveRecord;
}
