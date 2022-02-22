<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m180319_094924_create_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer(),
            'parent_id' => $this->integer(),
            'name' => $this->string(255),
            'json_template' => $this->text()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%category}}');
    }
}
