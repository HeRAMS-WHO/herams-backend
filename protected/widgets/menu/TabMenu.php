<?php


namespace prime\widgets\menu;

use prime\interfaces\CanCurrentUser;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use prime\interfaces\PageInterface;
use prime\models\ar\Permission;
use yii\helpers\Html;

/**
 * Class Menu
 * Implements a tab menu for admin pages
 * @package prime\widgets\menu
 */
class TabMenu extends Widget
{
    public array $tabs = [];
    /**
     * @var ?object Permissions are checked against this object
     */
    public ?object $permissionSubject = null;

    /**
     * This uses a global service locator for easier use.
     * @param array $route
     * @return bool
     */
    private function isCurrentPage(array $route): bool
    {
        return \Yii::$app->requestedRoute === $route[0]
            || \Yii::$app->requestedAction->uniqueId === $route[0];
    }

    private function isVisible(array $tab): bool
    {
        // Check if user has permission
        if (isset($tab['permission'])) {
            if (($this->permissionSubject instanceof CanCurrentUser && !$this->permissionSubject->canCurrentUser($tab['permission']))
                || !\Yii::$app->user->can($tab['permission'], $this->permissionSubject)
            ) {
                return false;
            }
        }

        if (isset($tab['visible']) && !$tab['visible']($tab, $this)) {
            return false;
        }


        return true;
    }

    protected function renderMenu(): string
    {
        if (empty($this->tabs)) {
            return '';
        }

        $result = Html::beginTag('div', ['class' => 'tabs']);
        foreach ($this->tabs as $tab) {
            if (!$this->isVisible($tab)) {
                continue;
            }

            $classes = ['btn', 'btn-tab'];
            if ($this->isCurrentPage($tab['url'])) {
                $classes[] = 'active';
            }

            $classes += ($tab['class'] ?? []);

            $result .=  Html::a($tab['title'], $tab['url'], [
                'class' => $classes,
            ]);
        }
        $result .= Html::endTag('div');

        return $result;
    }

    public function run(): string
    {
        return $this->renderMenu();
    }
}
