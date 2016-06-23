<?php
use \yii\helpers\Html;
/**
 * @var \prime\reportGenerators\oscar\Generator $generator
 * @var \yii\i18n\Formatter $formatter;
 */

// Begin block, this makes sure it is not rendered when all variables it uses are empty.
$generator->beginBlock();
?>
<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-2 hcin-img-cont">
            <img class="hcin-img" src="data:image/jpg;base64,<?=base64_encode(file_get_contents($logo))?>">
        </div>
        <div class="col-xs-10">
            <h3 class="hcin-title"><?=$title; ?></h3>
            <div class="row">
                <table class="hcin">
                    <?php
                    foreach($data as $title => $value) {
                        $generator->beginBlock();
                        echo "<tr>";
                        echo Html::tag('td', $value, [
                            'class' => 'col-xs-2'
                        ]);
                        echo Html::tag('td', $title, [
                            'class' => 'col-xs-10'
                        ]);
                        echo "</tr>";
                        $generator->endBlock();
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$generator->endBlock();
?>