<?php

declare(strict_types=1);

use Collecthor\DataInterfaces\VariableInterface;
use prime\assets\ReactAsset;
use prime\components\View;
use prime\interfaces\WorkspaceForTabMenu;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;

ReactAsset::register($this);
/**
 * @var View $this
 */
$this->title = Yii::t('app', 'Users');
/**
 * @var View $this
 * @var WorkspaceForTabMenu $tabMenuModel
 * @var array $workspace
 * @var iterable<VariableInterface> $variables
 */


$this->beginBlock('tabs');
echo WorkspaceTabMenu::widget(
    [
        'workspace' => $tabMenuModel,
    ]
);
$this->endBlock();
Section::begin([]);
?>
    <div id="WorkspaceUserRoles"
         data-workspace-id="<?= $workspace['id'] ?>"></div>

<?php
Section::end();
?>