<?php

declare(strict_types=1);

namespace prime\controllers\permission;

use prime\helpers\ProposedGrant;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\values\Authorizable;
use SamIT\abac\values\Grant as GrantValue;
use yii\base\Action;
use yii\web\Response;

class Grant extends Action
{
    public function run(
        AuthManager $abacManager,
        Resolver $abacResolver,
        AccessCheckInterface $accessCheck,
        Response $response,
        string $source_id,
        string $target_id,
        string $source_name,
        string $target_name,
        string $permission
    ) {
        $source = new Authorizable($source_id, $source_name);
        $target = new Authorizable($target_id, $target_name);
        $proposedGrant = new ProposedGrant(
            $abacResolver->toSubject($source),
            $abacResolver->toSubject($target),
            $permission
        );
        $accessCheck->requirePermission($proposedGrant, Permission::PERMISSION_CREATE);

        $abacManager->getRepository()->grant(new GrantValue($source, $target, $permission));
        $response->format = Response::FORMAT_JSON;
        return $response;
    }
}
