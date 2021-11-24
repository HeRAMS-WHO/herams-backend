<?php

declare(strict_types=1);

use kartik\grid\GridView;
use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\interfaces\ResponseForList;
use prime\models\ar\Permission;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * @var ActiveDataProvider $responseProvider
 * @var View $this
 * @var FacilityForTabMenu $facility
 */

$this->title = $facility->getTitle();

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    ['facility' => $facility]
);
$this->endBlock();

Section::begin()
    ->withActions([
        [
            'label' => \Yii::t('app', 'Update situation for facility'),
            'link' => Url::to(['copy-latest-response', 'id' => $facility->getId()]),
            'permission' => Permission::PERMISSION_WRITE
        ],
    ])
    ->withSubject($facility)
    ->withHeader(\Yii::t('app', 'Responses'));

echo GridView::widget([
    'dataProvider' => $responseProvider,
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
