<?php

namespace prime\models\forms\projects;

use prime\models\ar\Workspace;

class CreateUpdate extends Workspace
{
    public function scenarios()
    {
        $scenarios =  [
            'create' => [
                'title',
                'owner_id',
                'token'
            ],
              'update' => [
                'title',
            ],
        ];
        $scenarios['admin-update'] = array_merge(['owner_id'], $scenarios['update']);
        return $scenarios;
    }

    public static function tableName()
    {
        return Workspace::tableName();
    }
}