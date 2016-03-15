<?php
namespace Helper;

use \Yii;
// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{

    public function runMigrations() {
        !defined('YII_ENV') && define('YII_ENV', 'codeception');
        Yii::$container = new \yii\di\Container();
        Yii::$app = new \yii\console\Application(require __DIR__ . '/../../../../config/console.php');

        /** @var \yii\console\controllers\MigrateController $controller */
        $controller = new \yii\console\controllers\MigrateController('controller', Yii::$app, [
            'interactive' => false,
            'migrationPath' => __DIR__ . '/../../../../migrations'
        ]);
        if ($controller->runAction('up') != 0) {
            die('no');
        };
    }
}
