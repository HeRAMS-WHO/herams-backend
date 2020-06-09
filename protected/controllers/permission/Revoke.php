<?php
declare(strict_types=1);

namespace prime\controllers\permission;


use prime\helpers\ProposedGrant;
use prime\models\ar\Permission;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\values\Authorizable;
use SamIT\abac\values\Grant;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\User;

class Revoke extends Action
{
    public function run(
        User $user,
        AuthManager $abacManager,
        Resolver $abacResolver,
        \yii\web\Response $response,
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
            $permission);
        if (!$user->can(Permission::PERMISSION_DELETE, $proposedGrant)) {
            throw new ForbiddenHttpException();
        }

        $abacManager->getRepository()->revoke(new Grant($source, $target, $permission));
        $response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }
}