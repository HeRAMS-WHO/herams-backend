<?php
declare(strict_types=1);

use kartik\grid\GridView;
use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\interfaces\ResponseForList;
use prime\models\ar\Permission;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * @var ActiveDataProvider $responseProvider
 * @var View $this
 * @var FacilityForTabMenu $facility
 */

$this->title = $facility->title();

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
        ResponseForList::ID,
        'externalId',
        'dateOfUpdate',
        ResponseForList::CONDITION,
        ResponseForList::FUNCTIONALITY,
        ResponseForList::ACCESSIBILITY,


    ]
]);

Section::end();
