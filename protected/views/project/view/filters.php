<?php

use prime\helpers\Icon;
use prime\models\forms\ResponseFilter as ResponseFilter;
use yii\helpers\Html;
use yii\helpers\Json as Json;
use yii\helpers\Url;

/* @var \yii\web\View $this */
/* @var \prime\models\ar\Project $project */
/* @var ResponseFilter $filterModel */

echo Html::beginForm(['project/view', 'id' => $project->id,
    'page_id' => \Yii::$app->request->getQueryParam('page_id'),
    'parent_id' => \Yii::$app->request->getQueryParam('parent_id')
], 'get', [
    'autocomplete' => 'off',
    'class' => 'filters'
]);

    ?>
    <div class="count">
        <?php
        echo Icon::healthFacility() . ' ' . \Yii::t('app', 'Health Facilities');
        echo Html::tag('em', count($data));
        ?>
    </div>
    <div class="count">
        <?php
        echo Icon::contributors() . ' ' . \Yii::t('app', 'Contributors');
        echo Html::tag('em', $project->getContributorCount());
        ?>
    </div>
    <div class="count">
        <?php
        echo Icon::sync() . ' ' . \Yii::t('app', 'Latest update');
        /** @var \prime\objects\HeramsResponse $heramsResponse */
        $lastUpdate = null;
        foreach($data as $heramsResponse) {
            $date = $heramsResponse->getDate();
            if (!isset($lastUpdate) || (isset($date) && $date->greaterThan($lastUpdate))) {
                $lastUpdate = $date;
            }
        }
        echo Html::tag('em', $lastUpdate? $lastUpdate->diffForHumans() : \Yii::t('app', 'N/A'));
        ?>
    </div>
    <?php
        echo Html::a('Filters', '#', ['id' => 'filter-expand', 'style' => [
            'float' => 'right',
            'box-shadow' => 'none',
            'background-color' => 'gray',
            'color' => 'white',
            'padding' => '10px',
            'border' => 'none'
        ]]);
        $this->registerJs(<<<JS
        $('#filter-expand').on('click', function() {
            $(this).parent().toggleClass('expanded');
        });

JS
        )

    ?>
    <div class="advanced">
        <div class="filter filter_search">
            <input id="search-filter">
            <ul class="hint">
                <li>You may search for multiple terms, only results that contain all terms are shown</li>
                <li>Search also uses the group name, for example try typing "Trauma"</li>
                <li>After closing this screen you must click <b>Apply filters</b> to see the changes</li>
            </ul>
        </div>
        <?php
        echo $this->render('filterForm', ['filterModel' => $filterModel, 'project' => $project]);

        ?>



            <?php

            $this->registerJs(<<<JS
    document.getElementById('search-filter').addEventListener('input', function(e) {
        // Add debounce.
        clearTimeout(window.searchTimer);
        window.searchTimer = setTimeout(function() {
            let tokens = e.target.value.toLocaleUpperCase().split(' ').filter(x => x.length > 1);
            
            document.querySelectorAll('.advanced label.control-label[data-keywords]').forEach(function(el) {
                let hidden = !tokens.every((token) => el.getAttribute('data-keywords').toLocaleUpperCase().includes(token));
                el.parentNode.parentNode.classList.toggle('hidden', hidden);
                if (!hidden && tokens.length > 0) {
                    el.innerHTML = el.innerText.replace(new RegExp(tokens.join('|'), "gi"), function(match, contents, offset, input_string) {
                        return '<b>' + match + '</b>';
                    });
                } else {
                    el.innerHTML = el.innerText;
                }
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
                    'parent_id' => \Yii::$app->request->getQueryParam('parent_id')
                ])) ?>;
            })
        </script>
        <button type="submit"><i class="fas fa-check"></i> Apply all</button>
    </div>


<?php
    echo Html::endForm();