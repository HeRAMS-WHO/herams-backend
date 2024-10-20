<?php

use herams\common\helpers\InsecureSecretEnvironment;
use herams\common\helpers\KubernetesSecretEnvironment;

define('YII_DEBUG', true || file_exists(__DIR__ . '/config/debug'));
define('YII_ENV', getenv('YII_ENV'));
defined('CONSOLE') or define('CONSOLE', true);

defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', file_exists(__DIR__ . '/config/debug') ? 3 : 0);
require_once __DIR__ . '/../../../vendor/autoload.php';
spl_autoload_register(['Yii', 'autoload'], true, true);

error_reporting(E_ALL & ~E_DEPRECATED);

// Detect if we are in K8s...
if (! file_exists('/run/secrets')) {
    $env = new InsecureSecretEnvironment('/run/env.json', __DIR__ . '/config/env.json');
} else {
    $env = new KubernetesSecretEnvironment('/run/secrets', __DIR__ . '/config/env.json', '/run/config/config.json', '/run/env.json');
}

$containerConfigurator = require __DIR__ . '/config/console.php';

$containerConfigurator($env, \Yii::$container);

\Yii::$container->get(\yii\console\Application::class)->run();
