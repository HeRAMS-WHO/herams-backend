<?php
declare(strict_types=1);

use prime\components\View;
use prime\models\ar\Project;
use prime\models\forms\project\SyncWorkspaces;
use prime\widgets\Section;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var View $this
 * @var Project $project
 * @var SyncWorkspaces $model
 */

$this->title = $project->title;

Section::begin(['options'=> ['style' => ['column-width' => '500px']]])->withHeader(\Yii::t('app', 'Sync workspaces'));
echo Html::beginTag('table', ['id' => 'progress-table']);
echo Html::beginTag('thead');
echo Html::beginTag('tr');
echo Html::tag('th', \Yii::t('app', 'Workspace'));
echo Html::tag('th', \Yii::t('app', 'New'));
echo Html::tag('th', \Yii::t('app', 'Updated'));
echo Html::tag('th', \Yii::t('app', 'Deleted'));
echo Html::tag('th', \Yii::t('app', 'Unchanged'));
echo Html::tag('th', \Yii::t('app', 'Failed'));
echo Html::tag('th', \Yii::t('app', 'Duration'));
echo Html::endTag('tr');
echo Html::endTag('thead');
foreach ($model->getSelectedWorkspaces() as $workspace) {
    echo Html::beginTag('tr', [
        'style' => [
            'padding' => '10px',
            'break-inside' => 'avoid'
        ],
        'data-uri' => Url::to(['/api/workspace/refresh', 'id' => $workspace->id]),
        'class' => 'pending'
    ]);
    echo Html::tag('td', $workspace->title);
    echo Html::tag('td', '', ['data-attribute' => 'new']);
    echo Html::tag('td', '', ['data-attribute' => 'updated']);
    echo Html::tag('td', '', ['data-attribute' => 'deleted']);
    echo Html::tag('td', '', ['data-attribute' => 'unchanged']);
    echo Html::tag('td', '', ['data-attribute' => 'failed']);
    echo Html::tag('td', '', ['data-attribute' => 'time']);
    echo Html::endTag('tr');
}
echo Html::endTag('table');
Section::end();

$css = <<<CSS

.pending {
    background-color: var(--light-yellow);
}

.busy {
    background-color: var(--light-orange);
}

.complete {
    background-color: var(--light-green);
}

.fail {
    background-color: var(--light-red);
}

.fail td[data-attribute=time] {
    cursor: pointer;
}
.fail td[data-attribute=time]:before {
    content: ">>"
}
CSS;

$js = <<<JS
    let config = {
        headers: {
            'X-CSRF-Token': yii.getCsrfToken(),
            'accept': 'application/json'
        },
        method: 'POST',
        mode: 'cors',
        redirect: 'error',
        referrer: 'no-referrer',
        credentials: 'same-origin'
    };
    
    let syncNext = async () => {
        let elem = document.querySelector('.pending');
        if (elem) {
            elem.classList.remove('pending');
            elem.classList.add('busy');
            try {
                response = await fetch(elem.getAttribute('data-uri'), config);
                body = await response.json();
                elem.querySelectorAll('[data-attribute]').forEach((e) => {
                    e.textContent = body[e.getAttribute('data-attribute')];
                })
                    
                elem.classList.remove('busy');
                elem.classList.add(response.ok ? 'complete' : 'fail');
            } catch (error) {
                elem.classList.remove('busy');
                elem.classList.add('fail');
                throw error
            }
            
            
        }
    };
    
    document.getElementById('progress-table').addEventListener('click', (e) => {
        let target = e.target.closest('.fail td[data-attribute=time]');
        if (target) {
            let row = target.closest('tr');
            row.classList.remove("fail");
            row.classList.add("pending");
            syncAll();
        }
        
    })
    let syncAll = async () => {
        while(document.querySelector('.pending')) {
            let response = await syncNext();
        }
    }
    
    syncAll();

JS;

$this->registerCss($css);
$this->registerJs($js);
