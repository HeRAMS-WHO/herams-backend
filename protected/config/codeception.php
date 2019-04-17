<?php

$env = new \prime\components\Environment();
$config = require 'web.php';
$config['components']['limesurvey'] = \prime\tests\_helpers\LimesurveyStub::class;
return $config;