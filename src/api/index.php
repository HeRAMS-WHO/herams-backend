<?php
declare(strict_types=1);

use prime\components\KubernetesSecretEnvironment;

defined('CONSOLE') or define('CONSOLE', false);
/**
 * Valid values are dev and production.
 * A configuration file with the same name will be included if it exists.
 */
define('YII_ENV', getenv('YII_ENV'));
define('YII_DEBUG', true);
defined('YII_DEBUG') or define('YII_DEBUG', file_exists(__DIR__ . '/config/debug'));

error_reporting(E_ALL & ~E_DEPRECATED);
(static function () {
    $autoload = __DIR__ . '/../../vendor/autoload.php';
    if (! file_exists($autoload)) {
        die("Could not locate composer autoloader");
    }

    require_once $autoload;
    if (! file_exists('/run/secrets')) {
        $env = new \prime\components\InsecureSecretEnvironment('/run/env.json', __DIR__ . '/config/env.json');
    } else {
        $env = new KubernetesSecretEnvironment('/run/secrets', __DIR__ . '/config/env.json', '/run/config/config.json', '/run/env.json');
    }

    $containerConfigurator = require __DIR__ . '/../config/api/api.php';

    $containerConfigurator($env, \Yii::$container);
    $app = \Yii::$container->get(\yii\web\Application::class);
    $app->run();
})();
