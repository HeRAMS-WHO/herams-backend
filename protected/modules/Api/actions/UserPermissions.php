<?php

declare(strict_types=1);

namespace prime\modules\Api\actions;

use prime\helpers\ArrayHelper;
use prime\models\ar\Permission;
use prime\models\ar\User;
use SamIT\abac\AuthManager;
use yii\base\Action;
use yii\web\UrlManager;

class UserPermissions extends Action
{
    /**
     * @var class-string
     */
    public string $target;

    public function run(
        UrlManager $urlManager,
        AuthManager $abacManager,
        int $id
    ) {
        $permissions = Permission::find()
            ->andWhere([
                'source' => User::class,
                'target_id' => $id,
                'target' => $this->target,
            ])
            ->select(['source_id', 'permission'])
            ->all();

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
            $data[$permission->source_id]['permissions'][$permission->permission] = true
            ;
        }
        return $this->controller->asJson(array_values($data));

//
    }
}
