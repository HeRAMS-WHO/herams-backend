<?php
declare(strict_types=1);

namespace prime\widgets\FavoriteColumn;

use prime\helpers\Icon;
use prime\models\ar\User;
use prime\models\ar\Workspace;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\Url;

class FavoriteColumn extends DataColumn
{
    public bool $enableClick = true;
    public $route;
    public User $user;

    public function init()
    {
        $this->user = \Yii::$app->user->identity;
        $this->value = $this->value ?? static fn(object $model) => $model;
        $this->attribute = $this->attribute ?? 'favorite';
        if (!isset($this->route)) {
            $this->route = ['/api/user/workspaces', 'id' => \Yii::$app->user->id];
        }

        $targetIds = $this->user->getFavorites()->workspaces()->
            select('target_id')->indexBy('target_id')->column();
        parent::init();
        $this->content = function ($model, $key, $index, self $column) use ($targetIds) {
            $model = $this->getDataCellValue($model, $key, $index);
            if (!$model instanceof Workspace) {
                return '';
            }

            return Html::button(Icon::star(), [
                'title' => \Yii::t('app', 'Favorites'),
                'class' => [
                    'FavoriteButton',
                    isset($targetIds[$model->id]) ? 'favorite' : null
                ],
                'data' => [
                    'uri' => Url::to(array_merge($column->route, ['target_id' => $model->id]))
                ],
            ]);
        };

        if ($this->enableClick) {
            $this->grid->view->registerJs(<<<JS

            document.addEventListener('click', async (e) => {
                let button = e.target.closest('td .FavoriteButton');
                if (button) {
                    
                    let className = 'favorite';
                    let desiredState = !button.classList.contains(className);
                    // Match!
                    button.disabled = true;
                    
                    let response = await fetch(button.getAttribute('data-uri'), {
                        headers: {
                            'X-CSRF-Token': yii.getCsrfToken(),
                        },
                        method: desiredState ? 'PUT' : 'DELETE',
                        mode: 'cors',
                        redirect: 'error',
                        referrer: 'no-referrer',
                        credentials: 'same-origin'
                    });
                    
                    button.disabled = false;
                    if (response.ok) {
                        button.classList.toggle(className, desiredState);
                    }
                }
            });

JS
            );
        }
    }
}
