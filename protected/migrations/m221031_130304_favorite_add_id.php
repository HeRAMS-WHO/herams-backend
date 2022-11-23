<?php

use yii\db\Migration;


class m221031_130304_favorite_add_id extends Migration
{
    public function up(): bool
    {
        $this->dropPrimaryKey('PRIMARY', '{{%favorite}}');
        $this->addColumn('{{%favorite}}', 'id', $this->primaryKey());
        $this->createIndex('unique', '{{%favorite}}', ['user_id', 'target_class', 'target_id'], true);
        return true;
    }

    public function down(): bool
    {
        $this->dropIndex('unique', '{{%favorite}}');
        $this->dropColumn('{{%favorite}}', 'id');
        $this->addPrimaryKey('PRIMARY', '{{%favorite}}', ['user_id', 'target_class', 'target_id']);
        return false;
    }
}
