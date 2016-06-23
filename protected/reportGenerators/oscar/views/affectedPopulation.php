<?php
use \yii\helpers\Html;
/**
 * @var \prime\reportGenerators\oscar\Generator $g
 * @var \yii\i18n\Formatter $f;
 */

$g->beginBlock();
?>
    <div class="col-xs-12" >

    <h2><?=\Yii::t('oscar', 'Affected population')?></h2>
    <div style="border: 1px solid #666; padding-bottom: 1em; display: flex; justify-content: space-around">
    <?php
    $blocks = [
        ['logo' => 'affected.jpg', 'text' => \Yii::t('oscar', '# People'), 'code' => 'genindic[SQ001]'],
        ['logo' => 'deaths.jpg', 'text' => \Yii::t('oscar', '# Deaths'), 'code' => '2genindic[SQ002]'],
        ['logo' => 'refugees.jpg', 'text' => \Yii::t('oscar', '# Refugees'), 'code' => 'genindic[SQ003]'],
        ['logo' => 'displaced.jpg', 'text' => \Yii::t('oscar', '# Internally displaced'), 'code' => 'genindic[SQ004]'],
        ['logo' => 'injured.jpg', 'text' => \Yii::t('oscar', '# Injured'), 'code' => 'genindic[SQ005]'],
    ];
    foreach($blocks as $block) {
        $g->beginBlock();
        echo Html::tag('div', implode("\n", [
            Html::img("data:image/jpg;base64," . base64_encode(file_get_contents(__DIR__ . ' /../assets/img/' . $block['logo']))),
            Html::tag('span', $block['text']),
            Html::tag('span', $f->asInteger($g->getQuestionValue($block['code'])))
        ]), [
                'class' => 'ap-block'
        ]);
        $g->endBlock();
    }
    ?>
    </div>
</div>
<?php

$g->endBlock();
?>