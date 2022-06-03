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
use yii\web\User;

/**
 * @var FacilityForTabMenu $facility
 * @var ActiveDataProvider $responseProvider
 * @var View $this
 * @var array $updateSituationUrl
 */

$this->title = $facility->getTitle();

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    [
        'facility' => $facility,
    ]
);
$this->endBlock();

Section::begin()
    ->withActions([
        [
            'label' => \Yii::t('app', 'Update situation for facility'),
            'link' => Url::to($updateSituationUrl),
            'permission' => function (FacilityForTabMenu $facility, User $userComponent) {
                return $facility->canReceiveSituationUpdate() && $facility->canCurrentUser(Permission::PERMISSION_SURVEY_DATA);
            },
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
    ],
]);

Section::end();
