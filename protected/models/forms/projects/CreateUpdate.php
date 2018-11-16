<?php

namespace prime\models\forms\projects;

use prime\models\ar\Project;

class CreateUpdate extends Project
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
        return Project::tableName();
    }
}