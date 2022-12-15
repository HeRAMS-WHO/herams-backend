<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m211208_085045_add_expression_results_to_facility extends Migration
{
    public function safeUp(): bool
    {
        $this->addColumn('{{%facility}}', 'can_receive_situation_update', $this->boolean()->after('longitude')->notNull()->defaultValue(true));
        $this->addColumn('{{%facility}}', 'use_in_list', $this->boolean()->after('can_receive_situation_update')->notNull()->defaultValue(true));
        $this->addColumn('{{%facility}}', 'use_in_dashboarding', $this->boolean()->after('use_in_list')->notNull()->defaultValue(true));

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%facility}}', 'can_receive_situation_update');
        $this->dropColumn('{{%facility}}', 'use_in_list');
        $this->dropColumn('{{%facility}}', 'use_in_dashboarding');

        return true;
    }
}
