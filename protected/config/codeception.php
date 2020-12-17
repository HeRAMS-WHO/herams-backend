<?php

$env = new \prime\components\InsecureSecretEnvironment();
$config = require 'web.php';
$config['components']['limesurvey'] = \prime\tests\_helpers\LimesurveyStub::class;
return $config;
