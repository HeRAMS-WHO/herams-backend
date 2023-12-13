<?php

declare(strict_types=1);

namespace herams\console\seeders;

use herams\common\models\Role;

final class RolesSeeder extends SeederBase
{
    public function run(): void
    {
        $rows = array_map('str_getcsv', file(__DIR__ . '/data/permissions_matrix.csv'));

        $csv = [];

        foreach ($rows as $row) {
            $csv[] = explode(';', $row[0]);
        }
        $roles = $csv[0];
        for ($i = 0; $i < count($roles); $i++) {
            if ($i == 0 || $i == 1 || $i == 2) {
                continue;
            }
            $scope = '';
            if ($i < 4) {
                $scope = 'global';
            }
            if ($i > 3 && $i < 10) {
                $scope = 'Project';
            }
            if ($i > 9) {
                $scope = 'Workspace';
            }
            $type = 'standard';
            $role = new Role();
            $role->name = str_replace('"', '', $roles[$i]);
            $role->scope = $scope;
            $role->type = $type;
            $role->project_id = null;
            $role->created_date = date('Y-m-d H:i:s');
            $role->created_by = null;
            $role->last_modified_date = date('Y-m-d H:i:s');
            $role->last_modified_by = null;
            $role->save();
        }
    }
}
