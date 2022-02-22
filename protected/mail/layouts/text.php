<?php

declare(strict_types=1);

/**
 * @var \prime\components\View $this
 * @var string $content main view render result
 */

$this->beginPage();
$this->beginBody();
echo $content;
$this->endBody();
$this->endPage();
