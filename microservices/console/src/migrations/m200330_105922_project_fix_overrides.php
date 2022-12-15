<?php
declare(strict_types=1);

namespace herams\console\migrations;

use herams\common\models\Project;
use yii\db\Migration;

class m200330_105922_project_fix_overrides extends Migration
{
    public function safeUp()
    {
        /** @var Project $project */
        foreach (Project::find()->each() as $project) {
            $project->save(true, ['overrides']);
        }
    }

    public function safeDown()
    {
        echo "m200330_105922_project_fix_overrides cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200330_105922_project_fix_overrides cannot be reverted.\n";

        return false;
    }
    */
}
