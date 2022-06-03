<?php

declare(strict_types=1);

use yii\db\Migration;

class m211026_103049_redo_facility extends Migration
{
    public function safeUp(): bool
    {
        $this->dropColumn('{{%facility}}', 'uuid');
        $this->addColumn('{{%facility}}', 'data', $this->json()->after('code'));
        $this->addColumn('{{%facility}}', 'admin_data', $this->json()->after('data'));
        $this->dropColumn('{{%facility}}', 'coordinates');
        $this->addColumn('{{%facility}}', 'latitude', $this->decimal(10, 8)->after('admin_data'));
        $this->addColumn('{{%facility}}', 'longitude', $this->decimal(11, 8)->after('latitude'));

        return true;
    }

    public function safeDown(): bool
    {
        $this->addColumn('{{%facility}}', 'uuid', ' binary(16) not null');
        $this->dropColumn('{{%facility}}', 'data');
        $this->dropColumn('{{%facility}}', 'admin_data');
        $this->dropColumn('{{%facility}}', 'latitude');
        $this->dropColumn('{{%facility}}', 'longitude');
        $this->addColumn('{{%facility}}', 'coordinates', ' point null');

        return true;
    }
}
