<?php
declare(strict_types=1);

namespace prime\controllers\permission;


use prime\helpers\ProposedGrant;
use prime\models\permissions\Permission;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class Delete extends Action
{
    public function run(
        User $user,
        Resolver $abacResolver,
        AuthManager $abacManager,
        int $id,
        string $redirect
    ) {
        $permission = Permission::findOne(['id' => $id]);
        if (!isset($permission)) {
            throw new NotFoundHttpException();
        }

        $source = $abacResolver->toSubject($permission->sourceAuthorizable());
        $target = $abacResolver->toSubject($permission->targetAuthorizable());
        $proposedGrant = new ProposedGrant($source, $target, $permission->permission);
        if (!$user->can(Permission::PERMISSION_DELETE, $proposedGrant)) {
            throw new ForbiddenHttpException();
        }

        $abacManager->getRepository()->revoke($permission->getGrant());
        return $this->controller->redirect($redirect);
    }
}