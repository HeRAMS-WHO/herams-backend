<?php
namespace {

    use yii\web\Application;

    define('TEST_ADMIN_ID', 1);
    define('TEST_USER_ID', 2);
    define('TEST_OTHER_USER_ID', 3);

    call_user_func(function () {
        require_once 'constants.php';

        $autoload = __DIR__ . '/../vendor/autoload.php';
        if (!file_exists($autoload)) {
            die("Could not locate composer autoloader");

        }

        require_once $autoload;
        \prime\models\ar\Permission::$enableCaching = false;

        $config = require __DIR__ . '/../protected/config/codeception.php';

        \Yii::$container->set(Application::class, $config);


        $base = __DIR__;
        if (!is_dir($base . '/_output')) {
            mkdir($base . '/_output');
        }
    });

}