<?php
declare(strict_types=1);

/**
 * @var \prime\components\View $this
 * @var string $content
 */
$this->beginContent('@views/layouts/map.php');
?>
<div class="popover popover-error">
    <?= $content; ?>
</div>
<?php

$this->endContent();
