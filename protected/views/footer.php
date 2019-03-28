<div class="footer">
<?php

use Carbon\Carbon;use prime\helpers\Icon;
use yii\helpers\Html;

echo Icon::mapMarkedAlt(['class' => 'subject']);
echo Html::tag('div', count($projects), [
    'class' => 'counter'
]);
echo Html::tag('div', 'HeRAMS projects', [
    'class' => 'subject'
]);
echo Html::beginTag('div', ['class' => 'status']);

echo Icon::sync();
echo " Last updated: ";
echo Html::tag('span', $projects[0]->title . ' / ' . Carbon::now()->subHour(mt_rand(1, 100))->diffForHumans(), ['class' => 'value']);

echo Html::endTag('div');

echo Html::a(Icon::chevronLeft(), '#', ['class' => 'left']);
echo Html::a(Icon::chevronRight(), '#', ['class' => 'right']);

echo Html::endTag('div');
?>

</div>
