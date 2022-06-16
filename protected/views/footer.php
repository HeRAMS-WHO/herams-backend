<div class="footer">
<?php

use prime\helpers\Icon;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use yii\helpers\Html;

// Render all statistics.
/**
 * @var Project[] $projects
 * @var \prime\components\View $this
 */
$projects = Project::find()->withFields('facilityCount', 'latestDate')->all();
$stats[] = [
    'icon' => Icon::project(),
    'count' => Project::find()->count(),
    'subject' => \Yii::t('app', 'HeRAMS projects')
];
$stats[] = [
    'icon' => Icon::contributors(),
    'count' => Permission::find()
        ->andWhere(['target' => 'prime\\models\\ar\\Workspace', 'source' => 'prime\\models\\ar\\User'])
        ->count('distinct [[source_id]]'),
        'subject' => \Yii::t('app', 'Contributors')
];

$stats[] = [
    'icon' => Icon::healthFacility(),
    'count' => \prime\models\ar\Response::find()
        ->count('distinct [[workspace_id]], [[hf_id]]'),
    'subject' => \Yii::t('app', 'Health facilities')
];

echo Html::beginTag('div', ['class' => 'stats']);
foreach ($stats as $stat) {
    echo Html::beginTag('div');
    echo $stat['icon'];
    echo Html::tag('div', $stat['count'], ['class' => 'counter']);
    echo Html::tag('div', $stat['subject'], ['class' => 'subject']);
    echo Html::endTag('div');
}
echo Html::endTag('div');

/** @var \prime\models\ar\Response $response */
$response = \prime\models\ar\Response::find()
    ->select(['workspace_id', 'date'])
    ->orderBy(['date' => SORT_DESC])
    ->limit(1)
    ->one();
/** @var Project $latest */
$latest = Project::find()
    ->andWhere(['id' => \prime\models\ar\Workspace::find()
        ->select('tool_id')
        ->andWhere(['id' => $response->workspace_id ?? null])
    ])->one();


if (isset($latest)) {
    $localizedDate = \Yii::$app->formatter->asDate($response->date, 'short');
    $status = "{$latest->title} / {$localizedDate}";
} else {
    $status = \Yii::t('app', "No data loaded");
}

echo Html::beginTag('div', [
    'class' => 'status',
    'title' => $status
]);
    echo Icon::recycling() . ' ';
    echo \Yii::t('app', 'Latest update') . ': ';
    echo Html::tag('span', $status, ['class' => 'value']);
echo Html::endTag('div');

echo Html::a(Icon::chevronLeft(), '#', ['class' => 'left', 'id' => 'footer-left']);
echo Html::a(Icon::chevronRight(), '#', ['class' => 'right', 'id' => 'footer-right']);

echo Html::endTag('div');
$this->registerJs(<<<JS
try {
    const footer = document.querySelector('.footer .stats');
    let timer;
    const moveRight = function() {
        footer.append(footer.firstChild);
        clearTimeout(timer);
        timer = setTimeout(moveRight, 5000);
    };
    timer = setTimeout(moveRight, 5000);
    
    document.getElementById('footer-right').addEventListener('click', moveRight);
    document.getElementById('footer-left').addEventListener('click', function() {
        footer.prepend(footer.lastChild);
        clearTimeout(timer);
        timer = setTimeout(moveRight, 5000);
    });
} catch (error) {
    console.error("Error in footer JS", error);
}

JS

);
?>

</div>
