<?php
namespace herams\console\seeders;
use Codeception\Command\Console;
use herams\common\models\Role;
use herams\common\models\RolePermission;
use Yii;
use yii\db\Exception; // Import the Exception class
use herams\common\models\Permission;

final class RolePermissionsSeeder extends SeederBase {
    public function run(): void
    {
        $rows = array_map('str_getcsv', file(__DIR__ . '/data/permissions_matrix.csv'));

        $csv = array();

        foreach ($rows as $row) {
            $csv[] = explode(';', $row[0]);
        }
        $headers = $csv[0];
        for($i = 1; $i < count($csv); $i++){
            $permission = new Permission();
            if (!$csv[$i][0] && $csv[$i][1]){
                continue;
            }
            $permissionCode = str_replace('"', '', $csv[$i][0]);
            $permission = Permission::findOne(['code' => $permissionCode]);

            foreach ($csv[$i] as $index => $value){
                $cellValue = str_replace('"', '', trim($value));
                if ($cellValue === 'x'){
                    $headerName = str_replace('"', '', $headers[$index]);
                    $role = Role::findOne(['name' => $headerName]);
                    $rolePermission = new RolePermission();
                    $rolePermission->role_id = $role->id;
                    $rolePermission->permission_code = $permission->code;
                    $rolePermission->created_date = date('Y-m-d H:i:s');
                    $rolePermission->created_by = null;
                    $rolePermission->last_modified_date = date('Y-m-d H:i:s');
                    $rolePermission->last_modified_by = null;
                    $rolePermission->save();
                }
            }

        }
    }
}