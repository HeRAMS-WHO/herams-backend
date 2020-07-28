<?php
declare(strict_types=1);

namespace prime\widgets\FavoriteColumn;

use prime\helpers\Icon;
use prime\models\ar\Workspace;
use yii\grid\Column;
use yii\helpers\Html;
use yii\helpers\Url;

class FavoriteColumn extends Column
{
    public $route;
    public function init()
    {
        if (!isset($this->route)) {
            $this->route = ['/api/user/workspaces', 'id' => \Yii::$app->user->id];
        }

        $targetIds = \Yii::$app->user->identity->getFavorites()->filterTargetClass(Workspace::class)->
            select('target_id')->indexBy('target_id')->column();
        parent::init();
        $this->content = static function ($model, $key, $index, self $column) use ($targetIds) {

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

        $id = json_encode($this->grid->options['id']);
        

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
