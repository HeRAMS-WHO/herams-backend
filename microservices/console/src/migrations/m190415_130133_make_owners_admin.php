<?php

declare(strict_types=1);

namespace herams\console\migrations;

use herams\common\domain\user\User;
use herams\common\models\Permission;
use herams\common\models\Workspace;
use yii\db\Migration;

class m190415_130133_make_owners_admin extends Migration
{
    public function safeUp()
    {
        /** @var Workspace $workspace */
        foreach (Workspace::find()->each() as $workspace) {
            $user = User::findOne([
                'id' => $workspace->owner_id,
            ]);
            if (! isset($user)) {
                continue;
            }
            Yii::$app->abacManager->grant($user, $workspace, Permission::PERMISSION_ADMIN);
            if (
                ! Permission::find()
                    ->andWhere([
                        'source_id' => $user->id,
                        'source' => get_class($user),
                    ])
                    ->andWhere([
                        'target_id' => $workspace->id,
                        'target' => get_class($workspace),
                    ])
                    ->andWhere([
                        'permission' => Permission::PERMISSION_ADMIN,
                    ])
                    ->exists()
            ) {
                throw new Exception("Permission granting failed workspace {$workspace->title} ({$workspace->id}}");
            }
        }
    }

    public function safeDown()
    {
        echo "m190415_130133_make_owners_admin cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190415_130133_make_owners_admin cannot be reverted.\n";

        return false;
    }
    */
}
