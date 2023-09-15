<?php
declare(strict_types=1);

namespace herams\console\seeders;
use Codeception\Command\Console;
use Yii;
use yii\db\Exception; // Import the Exception class
use herams\common\models\Permission;

final class PermissionsSeeder extends SeederBase
{
    public function run(): void
    {

        $rows = array_map('str_getcsv', file(__DIR__ . '/data/permissions_matrix.csv'));

        $csv = array();

        foreach ($rows as $row) {
            $csv[] = explode(';', $row[0]);
        }

        for($i = 1; $i < count($csv); $i++){
            $permission = new Permission();
            if (!$csv[$i][0] && $csv[$i][1]){
                $parent = str_replace('"', '', $csv[$i][1]);
                $parent = trim($parent);
                continue;
            }
            var_dump('Permission ' . $csv[$i][0]);

            $permission = new Permission();
            $permission->code = str_replace('"', '', $csv[$i][0]);
            $permission->name = str_replace('"', '', $csv[$i][1]);
            $permission->parent = str_replace('"', '', $parent);
            $permission->created_date = date('Y-m-d H:i:s');
            $permission->created_by = null;
            $permission->last_modified_date = date('Y-m-d H:i:s');
            $permission->last_modified_by = null;
            $permission->save();
        }

    }
}