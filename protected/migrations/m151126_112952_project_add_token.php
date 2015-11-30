<?php

use yii\db\Migration;

class m151126_112952_project_add_token extends Migration
{
    public function up()
    {
        $table = '{{%project}}';
        $this->addColumn($table, 'token', $this->string(35));
        $this->createIndex('token', $table, ['token'], true);
        $query = new \yii\db\Query();
        $transaction = $this->db->beginTransaction();
        foreach ($query->from($table)->select('id')->column() as $projectId) {
            if (1 !== $this->db->createCommand()->update($table, ['token' => \Yii::$app->security->generateRandomString(35)], ['id' => $projectId])->execute()) {
                throw new \Exception("Something went wrong while generating tokens.");
            }
        }
        return $transaction->commit();
    }

    public function down()
    {
        $this->dropColumn('{{%project}}', 'token');
        return true;
        echo "m151126_112952_project_add_token cannot be reverted.\n";

        return false;
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
