<?php

declare(strict_types=1);

use prime\components\AuditService;
use yii\behaviors\BlameableBehavior;

// Disable the audit service since we do not want to have this run on every unit test.
\Yii::$container->set(AuditService::class, [
    'class' => AuditService::class,
    'enabled' => false
]);

// Preserve blamable non-empty values since there is (normally) no user logged in during unit tests, otherwise
// created_by fields will receive NULL which is not allowed in the database.
// also set a value since users are not logged in
\Yii::$container->set(BlameableBehavior::class, [
    'class' => BlameableBehavior::class,
    'preserveNonEmptyValues' => true,
    'value' => TEST_USER_ID,
]);
