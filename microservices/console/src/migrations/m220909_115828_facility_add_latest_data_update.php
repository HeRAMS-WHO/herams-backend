<?php

declare(strict_types=1);

namespace herams\console\migrations;

use herams\common\domain\facility\Facility;
use herams\common\jobs\UpdateFacilityDataJob;
use League\Tactician\CommandBus;
use yii\db\Migration;

final class m220909_115828_facility_add_latest_data_update extends Migration
{
    public function __construct(
        private CommandBus $commandBus,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function up(): bool
    {
        $this->addColumn('{{%facility}}', 'latest_date', $this->date()->defaultValue(null));

        $transaction = $this->db->beginTransaction();
        try {
            foreach (Facility::find()->asArray()->select('id')->column() as $id) {
                $job = new UpdateFacilityDataJob($id);
                $this->commandBus->handle($job);
            }
            $transaction->commit();
        } catch (Throwable $t) {
            $transaction->rollBack();
            $this->dropColumn('{{%facility}}', 'latest_date');
            throw $t;
        }
        return true;
    }

    public function down(): bool
    {
        //        $this->dropColumn('{{%facility}}', 'latest_date');
        return true;
    }
}
