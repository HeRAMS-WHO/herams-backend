<?php
declare(strict_types=1);

use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\interfaces\ResponseForList;
use prime\models\ar\Permission;
use prime\models\ar\Response;
use prime\widgets\DrilldownColumn;
use prime\widgets\Section;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Url;

/**
 * @var \yii\data\ActiveDataProvider $responseProvider
 * @var \prime\components\View $this
 * @var \prime\interfaces\FacilityForTabMenu $facility
 *
 */

echo $this->render('_breadcrumbs', ['tabMenuModel' => $facility]);
$this->beginBlock('tabs');
echo \prime\widgets\menu\FacilityTabMenu::widget(
    ['facility' => $facility]
);
$this->endBlock();
Section::begin([
    'actions' => [
        [
            'label' => \Yii::t('app', 'Update situation for facility'),
            'link' => Url::to(['copy-latest-response', 'id' => $facility->getId()]),
            'permission' => Permission::PERMISSION_ADMIN
        ],
    ]
])->withSubject($facility)->withHeader(\Yii::t('app', 'Responses'));

echo GridView::widget([
    'dataProvider' => $responseProvider,
//    'filterModel' => $projectSearch,
    'layout' => "{items}\n{pager}",
    'columns' => [
        [
            'attribute' => ResponseForList::ID,
            'class' => DrilldownColumn::class,
            'icon' => Icon::pencilAlt(),
            'link' => fn(ResponseForList $response) => ['response/update', 'id' => $response->getId()]
        ],
        'externalId',
        'dateOfUpdate',
        'condition',
        'functionality',
        'accessibility',


    ]
]);

Section::end();
