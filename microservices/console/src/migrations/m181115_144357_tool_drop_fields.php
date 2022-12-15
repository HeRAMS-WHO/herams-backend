<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m181115_144357_tool_drop_fields extends Migration
{
    public function safeUp()
    {
        foreach (
            [
                'acronym',
                'image',
                'description',
                'intake_survey_eid',
                'thumbnail',
                'progress_type',
                'generators',
                'default_generator',
                'explorer_regex',
                'explorer_name',
                'explorer_geo_js_name',
                'explorer_geo_ls_name',
                'explorer_map',
                'explorer_show_services',
            ] as $column
        ) {
            $this->dropColumn('{{%tool}}', $column);
        }
    }

    public function safeDown()
    {
        echo "m181115_144357_tool_drop_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181115_144357_tool_drop_fields cannot be reverted.\n";

        return false;
    }
    */
}
