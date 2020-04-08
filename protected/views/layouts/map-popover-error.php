<?php

use prime\helpers\Icon;
use prime\models\ar\Project;
use prime\models\ar\User;

$this->beginContent('@views/layouts/map.php');


?>
<div class="popover popover-error">
    <div class="form">
        <?=$content; ?>
    </div>
    <?=$this->render('stats'); ?>
</div>
<?php

$this->endContent();