<?php

declare(strict_types=1);

namespace herams\api\actions;

use herams\common\domain\permission\PermissionRepository;
use herams\common\domain\user\User;
use SamIT\abac\values\Authorizable;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\web\UrlManager;

final class UserPermissions extends Action
{
    public string $target;

    public function run(
        UrlManager $urlManager,
        PermissionRepository $permissionRepository,
        int $id
    ) {

        $permissions = $permissionRepository->retrieveForTarget(new Authorizable((string) $id, $this->target));

        $users = User::find()
            ->andFilterWhere([
                'id' => ArrayHelper::getColumn($permissions, 'source_id'),
            ])
            ->select(['id', 'email', 'name'])
            ->indexBy('id')
            ->all()

        ;

        $data = [];
        foreach ($permissions as $permission) {
            if (! isset($data[$permission->source_id])) {
                $data[$permission->source_id] = [
                    ...$users[$permission->source_id],
                    'permissions' => [],
                ];
            }
            $data[$permission->source_id]['permissions'][$permission->permission] = $urlManager->createAbsoluteUrl(['permission/delete', 'id' => $permission->id])
            ;
        }
        return $this->controller->asJson(array_values($data));

//
    }
}
