<?php

declare(strict_types=1);

use yii\db\Migration;

class m210125_105354_project_add_create_hf extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'manage_implies_create_hf', $this->boolean()->defaultValue(true)->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%project}}', 'manage_implies_create_hf');
    }
}
