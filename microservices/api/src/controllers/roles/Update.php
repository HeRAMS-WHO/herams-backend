<?php

declare(strict_types=1);

namespace herams\api\controllers\roles;

use herams\common\helpers\CommonFieldsInTables;
use herams\common\models\Role;
use herams\common\models\RolePermission;
use yii\base\Action;
use yii\helpers\BaseInflector;

class Update extends Action
{
    public function run(int $id): array
    {
        RolePermission::deleteAll([
            'role_id' => $id,
        ]);
        $role = ! ! $id ? Role::findOne($id) : new Role();
        $data = \Yii::$app->request->post();
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);
        unset($data['id']);
        unset($data['createdBy']);
        unset($data['createdDate']);
        unset($data['lastUpdatedBy']);
        unset($data['lastUpdatedDate']);
        $commonFields = CommonFieldsInTables::forCreating();
        foreach ($data as $key => $value) {
            $snake = BaseInflector::underscore($key);
            $role->$snake = $value;
        }
        if (! $id) {
            $role->created_date = $commonFields['created_date'];
            $role->created_by = $commonFields['created_by'];
            $role->last_modified_by = $commonFields['last_modified_by'];
            $role->last_modified_date = $commonFields['last_modified_date'];
        }
        if (! $this->mustHaveAProjectAssigned($role)) {
            $role->project_id = null;
        }

        $role->last_modified_by = $commonFields['last_modified_by'];
        $role->last_modified_date = $commonFields['last_modified_date'];
        $role->save();

        foreach ($permissions as $permission) {
            $rolePermission = new RolePermission();
            $rolePermission->role_id = $role->id;
            $rolePermission->permission_code = $permission['value'];
            $rolePermission->created_date = $commonFields['created_date'];
            $rolePermission->created_by = $commonFields['created_by'];
            $rolePermission->last_modified_by = $commonFields['last_modified_by'];
            $rolePermission->last_modified_date = $commonFields['last_modified_date'];
            $rolePermission->save();
        }
        return $role->toArray();
    }

    private function mustHaveAProjectAssigned(?Role $role): bool
    {
        return strtolower($role->scope) === 'project' && strtolower($role->type) === 'custom';
    }
}
