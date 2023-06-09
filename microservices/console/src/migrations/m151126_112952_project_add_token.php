<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;
use yii\db\Query;

class m151126_112952_project_add_token extends Migration
{
    public function up()
    {
        $table = '{{%project}}';
        $this->addColumn($table, 'token', $this->string(35));
        $this->createIndex('token', $table, ['token'], true);
        $query = new Query();
        $transaction = $this->db->beginTransaction();
        foreach ($query->from($table)->select('id')->column() as $projectId) {
            if (1 !== $this->db->createCommand()->update($table, [
                'token' => Yii::$app->security->generateRandomString(35),
            ], [
                'id' => $projectId,
            ])->execute()) {
                throw new Exception("Something went wrong while generating tokens.");
            }
        }
        $transaction->commit();
    }

    public function down()
    {
        $this->dropColumn('{{%project}}', 'token');
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
