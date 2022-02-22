<?php

declare(strict_types=1);

namespace prime\widgets\PermissionColumn;

use prime\assets\PrettyCheckbox;
use prime\models\ar\Permission;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\Url;

class PermissionColumn extends DataColumn
{
    public $target;
    public $permission;

    public function init()
    {
        parent::init();
        $this->contentOptions['style']['text-align'] = 'center';
        PrettyCheckbox::register($this->grid->view);
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        /** @var ?Permission $permission */
        $permission = $this->getDataCellValue($model, $key, $index);

        $value = isset($permission);
        $revoke = Url::to([
            'permission/revoke',
            'source_id' => $model['source']->getId(),
            'target_id' => $this->target->getId(),
            'source_name' => $model['source']->getAuthName(),
            'target_name' => $this->target->getAuthName(),
            'permission' => $this->permission
        ]);
        $grant = Url::to([
            'permission/grant',
            'source_id' => $model['source']->getId(),
            'target_id' => $this->target->getId(),
            'source_name' => $model['source']->getAuthName(),
            'target_name' => $this->target->getAuthName(),
            'permission' => $this->permission
        ]);
        $label =
              Html::tag('div', Html::label('&nbsp;'), ['class' => ['state', 'p-success', 'p-on']])
            . Html::tag('div', Html::label('&nbsp;'), ['class' => ['state', 'p-danger', 'p-off']])
        . Html::tag('div', Html::label('&nbsp;'), ['class' => ['state', 'p-warning', 'p-is-indeterminate', 'p-smooth']]);
        $cb = Html::checkbox('perm', $value, [
            'data-revoke' => $revoke,
            'data-grant' => $grant
        ]);


        $this->grid->view->registerJs(<<<JS
    document.addEventListener('change', (e) => {
        if (!e.target.matches('.pretty input[type=checkbox][data-revoke], .pretty input[type=checkbox][data-grant]')) {
            return;
        }
        let desiredState = e.target.checked;
        // Reset state.
        e.target.indeterminate = true;
        e.target.disabled = true;
        fetch(desiredState ? e.target.getAttribute('data-grant') : e.target.getAttribute('data-revoke'), {
            headers: {
                'X-CSRF-Token': yii.getCsrfToken(),
            },
            method: 'POST',
            mode: 'cors',
            redirect: 'error',
            referrer: 'no-referrer',
            credentials: 'same-origin'
        }).then((response) => {
            e.target.indeterminate = false;
            e.target.disabled = false;
            
            if (!response.ok) {
                e.target.checked = !desiredState;    
            }
            
        }).catch((error) => {
            e.target.indeterminate = false;
            e.target.disabled = false;
            e.target.checked = !desiredState;    
        });
        
    });

JS
        );
        return Html::tag(
            'div',
            $cb . $label,
            [
                'class' => ['pretty', 'p-toggle', 'p-switch', 'p-has-indeterminate'],
                'style' => ['margin-right' => 0]
            ]
        );
        return $value ? 'Yes' : 'No';
    }
}
