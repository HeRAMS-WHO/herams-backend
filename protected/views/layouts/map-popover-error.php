<?php

use prime\helpers\Icon;
use prime\models\ar\Project;
use prime\models\ar\User;

$this->beginContent('@views/layouts/map.php');
?>
<div class="popover popover-error">
    <?= $content; ?>
    <?= $this->render('stats'); ?>
</div>
<?php

$this->endContent();