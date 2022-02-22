<?php

use yii\db\Migration;

class m160225_082821_add_fields_to_profile extends Migration
{
    public function up()
    {
        $this->addColumn('{{%profile}}', 'position', $this->text());
        $this->addColumn('{{%profile}}', 'phone', $this->text());
        $this->addColumn('{{%profile}}', 'phone_alternative', $this->text());
        $this->addColumn('{{%profile}}', 'other_contact', $this->text());
    }

    public function down()
    {
        return false;
    }
}
