<?php
declare(strict_types=1);

/**
 * @var \yii\web\View $this
 * @var FacilityForTabMenu $tabMenuModel
 */

use prime\interfaces\FacilityForTabMenu;

assert($tabMenuModel instanceof FacilityForTabMenu);
$this->params['breadcrumbs'][] = [
    'label' => $tabMenuModel->projectTitle(),
    'url' => ['project/workspaces', 'id' => $tabMenuModel->projectId()]
];
$this->params['breadcrumbs'][] = [
    'label' => $tabMenuModel->workspaceTitle(),
    'url' => ['workspace/facilities', 'id' => $tabMenuModel->workspaceId()]
];
$this->title = $tabMenuModel->title();
