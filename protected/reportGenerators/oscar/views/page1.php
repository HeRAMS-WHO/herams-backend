<?php
    $generator->beginBlock();
?>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project, 'number' => $number, 'from' => $from, 'until' => $until])?>
    <div class="row">
        <h1 class="col-xs-12"><?=$project->getLocality()?></h1>
    </div>
    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            \Yii::t('oscar', 'Level : {level}', ['level' => 'National']) . '<br>' . \Yii::t('oscar', 'Completed on: {completedOn}', ['completedOn' => $signature->getTime()->format($generator->dateFormat)]),
        ],
        'columnsInRow' => 2
    ]);
    ?>
    <hr>
    <div class="row">
        <?=$this->render('affectedPopulation', ['g' => $generator, 'f' => $formatter]); ?>
        <?php $generator->beginBlock(); ?>
        <div class="col-xs-12">
            <h2><?=\Yii::t('oscar', 'Highlights')?></h2>
            <?=$generator->getQuestionValue('highlHTML')?>
        </div>
        <?php $generator->endBlock(); ?>
    </div>
</div>
<?php
$generator->endBlock();
?>