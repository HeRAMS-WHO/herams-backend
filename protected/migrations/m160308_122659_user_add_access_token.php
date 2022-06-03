<?php

use yii\db\Migration;

class m160308_122659_user_add_access_token extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'access_token', $this->string(32));
        $this->update('{{%user}}', [
            'access_token' => new \yii\db\Expression('md5(concat(:seed, email, id, password_hash, auth_key, :seed))', [
                ':seed' => mt_rand(),
            ]),
        ]);
    }

    public function down()
    {
        echo "m160308_122659_user_add_access_token cannot be reverted.\n";
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
