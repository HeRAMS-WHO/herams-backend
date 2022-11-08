<?php

declare(strict_types=1);

use herams\common\interfaces\HeramsResponseInterface;
use prime\helpers\Icon;
use prime\models\forms\ResponseFilter as ResponseFilter;
use yii\helpers\Html;
use yii\helpers\Json as Json;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \herams\common\models\Project $project
 * @var ResponseFilter $filterModel
 * @var array $data
 */

echo Html::beginForm([
    'project/filter',
    'id' => $project->id,
    'page_id' => \Yii::$app->request->getQueryParam('page_id'),
    'parent_id' => \Yii::$app->request->getQueryParam('parent_id'),
], 'post', [
    'autocomplete' => 'off',
    'class' => 'filters topbar',
]);

?>
<div class="count">
    <?php
    echo Icon::healthFacility();
    echo Html::tag('span', \Yii::t('app', 'Health Facilities'));
    echo Html::tag('em', count($data));
    ?>
</div>
<div class="count">
    <?php
    echo Icon::contributors();
    echo Html::tag('span', \Yii::t('app', 'Contributors'));
    echo Html::tag('em', $project->contributorCount);
    ?>
</div>
<div class="count">
    <?php
    echo Icon::recycling();
    echo Html::tag('span', \Yii::t('app', 'Latest update'));
    /** @var HeramsResponseInterface $heramsResponse */
    $lastUpdate = null;
    foreach ($data as $heramsResponse) {
        $date = $heramsResponse->getDate();
        if (! isset($lastUpdate) || (isset($date) && $date->greaterThan($lastUpdate))) {
            $lastUpdate = $date;
        }
    }
    echo Html::tag('em', $lastUpdate ? $lastUpdate->diffForHumans() : \Yii::t('app', 'N/A'));
    ?>
</div>
<?php
$filterCount = ! empty($filterModel->date) ? 1 : 0;
$filterCount += count($filterModel->advanced);
$filterCountSpan = "<span>{$filterCount}</span>";

$params = Yii::$app->request->queryParams;
if (! array_key_exists('page_id', $params) && ! empty($project->mainPages)) {
    $params['page_id'] = $project->mainPages[0]->getid();
}
echo Html::a(\Yii::t('app', 'Print'), array_merge($params, [
    'project/pdf',
    'id' => $project->id,
]), [
    'class' => 'btn btn-white btn-icon',
    'title' => \Yii::t('app', 'Export this page to pdf'),
]);
unset($params['page_id']);
//echo Html::a(\Yii::t('app', 'Print all pages'), array_merge($params, ['project/pdf', 'id' => $project->id]), ['class' => 'btn btn-white btn-icon', 'title' => \Yii::t('app', 'Export all pages to pdf')]);
echo Html::a(Icon::list() . ' ' . \Yii::t('app', 'Workspaces'), [
    'project/workspaces',
    'id' => $project->id,
], [
    'class' => 'btn btn-white',
    'title' => \Yii::t('app', 'Go to workspaces'),
]);
echo Html::a(\Yii::t('app', 'Filters') . $filterCountSpan, '#', [
    'id' => 'filter-expand',
    'class' => 'btn btn-default',
]);

$this->registerJs(
    <<<JS
        $('#filter-expand').on('click', function() {
            $(this).parent().toggleClass('expanded');
        });

JS
)

?>
<div class="advanced">
    <div class="filter filter_search">
        <div class="input-container">
            <?php
                echo Icon::search();
                echo Html::textInput('search', null, [
                    'id' => 'search-filter',
                    'placeholder' => \Yii::t('app', 'Search'),
                ]);
                ?>
        </div>
        <ul class="hint">
            <?php
                echo Html::tag('li', \Yii::t('app', 'You may search for multiple terms, only results that contain all terms are shown'));
                echo Html::tag('li', \Yii::t('app', 'Search also uses the group name, for example try typing "Trauma"'));
                echo Html::tag('li', \Yii::t('app', 'After closing this screen you must click {{apply}} to see the changes', [
                    'apply' => Html::tag('em', \Yii::t('app', 'Apply filters')),
                ]));
                ?>
        </ul>
    </div>
    <?php
    echo $this->render('filterForm', [
        'filterModel' => $filterModel,
        'project' => $project,
    ]);

    ?>



    <?php

    $this->registerJs(
        <<<JS
    document.getElementById('search-filter').addEventListener('input', function(e) {
        // Add debounce.
        clearTimeout(window.searchTimer);
        window.searchTimer = setTimeout(function() {
            let tokens = e.target.value.toLocaleUpperCase().split(' ').filter(x => x.length > 1);
            
            document.querySelectorAll('.advanced label.control-label[data-keywords]').forEach(function(el) {
                let hidden = !tokens.every((token) => el.getAttribute('data-keywords').toLocaleUpperCase().includes(token));
                el.parentNode.classList.toggle('hidden', hidden);
                if (!hidden && tokens.length > 0) {
                    el.innerHTML = el.innerText.replace(new RegExp(tokens.join('|'), "gi"), function(match, contents, offset, input_string) {
                        return '<b>' + match + '</b>';
                    });
                } else {
                    el.innerHTML = el.innerText;
                }
                
                const group = el.parentNode.parentNode;
                group.classList.toggle('hidden', group.querySelectorAll('.form-group:not(.hidden)').length === 0);
            })
        
        }, 300);
    });
JS
    );


    ?>

</div>
<div class="buttons" style="display: none;">
    <button type="button" id="clear"><i class="fas fa-times"></i> Clear all</button>
    <script>
        document.getElementById('clear').addEventListener('click', function() {
            window.location.href = <?= Json::encode(Url::to([
                'project/view',
                'id' => $project->id,
                'page_id' => \Yii::$app->request->getQueryParam('page_id'),
                'parent_id' => \Yii::$app->request->getQueryParam('parent_id'),
            ])) ?>;
        })
    </script>
    <button type="submit"><i class="fas fa-check"></i> Apply all</button>
</div>


<?php
echo Html::endForm();
