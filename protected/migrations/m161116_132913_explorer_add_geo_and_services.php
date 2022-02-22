<?php

use yii\db\Migration;

class m161116_132913_explorer_add_geo_and_services extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tool}}', 'explorer_geo_js_name', $this->string()->defaultValue(null));
        $this->addColumn('{{%tool}}', 'explorer_geo_ls_name', $this->string()->defaultValue(null));
        $this->addColumn('{{%tool}}', 'explorer_map', 'MEDIUMBLOB default NULL');
        $this->addColumn('{{%tool}}', 'explorer_show_services', $this->boolean()->defaultValue(false)->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%tool}}', 'explorer_geo_js_name');
        $this->dropColumn('{{%tool}}', 'explorer_geo_ls_name');
        $this->dropColumn('{{%tool}}', 'explorer_map');
        $this->dropColumn('{{%tool}}', 'explorer_show_services');
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
