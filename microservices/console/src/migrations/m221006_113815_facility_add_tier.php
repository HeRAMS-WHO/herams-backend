<?php

declare(strict_types=1);

namespace herams\console\migrations;

use herams\common\domain\facility\Facility;
use herams\common\jobs\UpdateFacilityDataJob;
use League\Tactician\CommandBus;
use yii\db\Migration;

final class m221006_113815_facility_add_tier extends Migration
{
    public function __construct(
        private CommandBus $commandBus,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function up(): bool
    {
        $this->addColumn('{{%facility}}', 'tier', $this->tinyInteger()->null());
        $transaction = $this->db->beginTransaction();
        try {
            foreach (Facility::find()->asArray()->select('id')->column() as $id) {
                $job = new UpdateFacilityDataJob($id);
                $this->commandBus->handle($job);
            }
            $transaction->commit();
        } catch (Throwable $t) {
            $transaction->rollBack();
            $this->dropColumn('{{%facility}}', 'tier');
            throw $t;
        }
        return true;
    }

    public function down()
    {
        $this->dropColumn('{{%facility}}', 'tier');
        return false;
    }
}
