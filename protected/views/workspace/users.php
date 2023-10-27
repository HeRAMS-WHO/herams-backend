<?php

declare(strict_types=1);

use Collecthor\DataInterfaces\VariableInterface;
use prime\components\View;
use prime\assets\ReactAsset;
use prime\widgets\menu\WorkspaceTabMenu;
use prime\widgets\Section;

ReactAsset::register($this);
/**
 * @var View $this
 */
$this->title = \Yii::t('app', 'Users');
/**
 * @var View $this
 * @var \prime\interfaces\WorkspaceForTabMenu $tabMenuModel
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
<div id="WorkspacesUsers"></div>

<?php
Section::end();
?>