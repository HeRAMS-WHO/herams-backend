<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%key}}`.
 */
class m191203_140540_CreateKeyTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%key}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'hash' => $this->string(255)->notNull()->append('COLLATE ascii_bin'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%key}}');
    }
}
