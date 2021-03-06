<?php

use yii\db\Migration;

/**
 * Class m200330_105922_project_fix_overrides
 */
class m200330_105922_project_fix_overrides extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** @var \prime\models\ar\Project $project */
        foreach (\prime\models\ar\Project::find()->each() as $project) {
            $project->save(true, ['overrides']);
        }
    }

    /**
     * {@inheritdoc}
     */
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
