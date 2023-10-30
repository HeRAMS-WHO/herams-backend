<?php

declare(strict_types=1);

use herams\common\models\Project;
use prime\assets\ReactAsset;
use prime\components\View;
use prime\widgets\menu\ProjectTabMenu;
use prime\widgets\Section;

ReactAsset::register($this);
/**
 * @var Project $project
 * @var View $this
 */

$this->params['subject'] = $project->getTitle();
$this->title = Yii::t(
    'app',
    "Users in project {project}",
    [
        'project' => $project->getTitle(),
    ]
);

$this->beginBlock('tabs');
echo ProjectTabMenu::widget([
    'project' => $project,
]);
$this->endBlock();
Section::begin();
?>
    <div id="UserRoles" data-project-id="<?= $project->id; ?>"></div>
<?php
Section::end();
