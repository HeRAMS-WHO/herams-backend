<?php

use yii\db\Migration;

class m160407_132410_add_manager_role extends Migration
{

    public function safeUp()
    {
        if (null === $authManager = \Yii::$app->getAuthManager()) {
            throw new \RuntimeException("Could not find auth manager.");
        }

        $manager = $authManager->createRole('manager');
        $manager->description = "Role that allows a user to read all projects.";
        return $authManager->add($manager) && $authManager->addChild($authManager->getRole('admin'), $manager);
    }

    public function safeDown()
    {
        if (null === $authManager = \Yii::$app->getAuthManager()) {
            throw new \RuntimeException("Could not find auth manager.");
        }

        return $authManager->remove($authManager->getRole('manager'));
    }

}
