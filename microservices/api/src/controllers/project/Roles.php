<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\domain\project\ProjectRepository;
use herams\common\models\Role;
use herams\common\values\ProjectId;
use yii\base\Action;

class Roles extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        int $id
    ) {
        $roles = [];
        $projectId = new ProjectId($id);
        $project = $projectRepository->retrieveById($projectId);
        $rolesInProject = $project->roles;
        foreach($rolesInProject as $role){
            $roles[$role->id] = [...$role];
        }
        $generalRoles = Role::find()
            ->where(['<>', 'scope', 'global'])
            ->andFilterWhere(['=', 'type', 'standard'])
            ->all();
        foreach($generalRoles as $role){
            $roles[$role->id] = [...$role];
        }
        $flattenRoles = [];
        foreach($roles as $role){
            $flattenRoles[] = $role;
        }
        return $flattenRoles;
    }
}
