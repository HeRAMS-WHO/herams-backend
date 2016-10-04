<?php
/**
 * @var \prime\reportGenerators\oscar\Generator $generator
 * @var \yii\i18n\Formatter $formatter;
 */

// Begin block, this makes sure it is not rendered when all variables it uses are empty.
$generator->beginBlock();
?>

<h3 class="col-xs-12"><?=\Yii::t('oscar', 'Funding (in US$)')?></h3>
<div class="col-xs-12">
    <table id="resource">
        <thead>
        <tr>
            <th></th>
            <th><?=\Yii::t('oscar', 'Required')?></th>
            <th><?=\Yii::t('oscar', 'Funded')?></th>
            <th><?=\Yii::t('oscar', '% funded')?></th>
        </tr>
        </thead>
        <?php $generator->beginBlock(); ?>
        <tr class="text-right">
            <td><?=\Yii::t('oscar', 'WHO')?></td>
            <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmwho_SQ001]'))?></td>
            <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmwho_SQ002]'))?></td>
            <td><?=$formatter->asPercent(
                    $generator->getPercentage('resmob2[rmwho_SQ002]', 'resmob2[rmwho_SQ001]')
                )?></td>
        </tr>
        <?php
            $generator->endBlock();
            $generator->beginBlock();
        ?>
        <tr>
            <td><?=\Yii::t('oscar', 'Health sector')?></td>
            <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmhc_SQ001]'))?></td>
            <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmhc_SQ002]'))?></td>
            <td><?=$formatter->asPercent(
                    $generator->getPercentage('resmob2[rmhc_SQ002]', 'resmob2[rmhc_SQ001]')
                )?></td>
        </tr>
        <?php
            $generator->endBlock();
            $generator->beginBlock();
        ?>
        <tr>
            <td><?=\Yii::t('oscar', 'Total')?></td>
            <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmwho_SQ001]') + $generator->getQuestionValue('resmob2[rmhc_SQ001]'))?></td>
            <td><?=$formatter->asInteger($generator->getQuestionValue('resmob2[rmwho_SQ002]') + $generator->getQuestionValue('resmob2[rmhc_SQ002]'))?></td>
            <td><?=$formatter->asPercent($generator->getPercentage(['resmob2[rmhc_SQ002]', 'resmob2[rmwho_SQ002]'],
                    ['resmob2[rmhc_SQ001]', 'resmob2[rmwho_SQ001]'])) ?></td>
        </tr>
        <?php
            $generator->endBlock();
        ?>
    </table>
</div>
<?php
    $generator->endBlock();

?>