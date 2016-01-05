<?php

use app\components\Html;

/**
 * @var \yii\web\View $this;
 */

$this->registerAssetBundle(\prime\assets\SassAsset::class);
$this->beginContent('@app/views/layouts/report.php');
?>
<style>
    table {
        border-collapse: separate;
        border-spacing: 3px;
    }
    td {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    tr > td:not(:first-child) {
        padding-left: 8px;
        padding-right: 8px;
    }

    .completed {
        background-color: green;
    }

    .not-completed {
        background-color: #ff8100;
    }
</style>

<div class="container-fluid">
<div class="row">
    <div class="col-xs-12">
        <h1><?=\Yii::t('app', 'Progress')?></h1>
    </div>
    <div class="col-xs-12">
<table>
    <?php
    $i = 1;
    foreach($progresses as $category => $finished) {
        echo "<tr><td>{$i}. {$category}</td><td style='width: 100px;'></td></td><td class='" . ($finished ? 'completed' : 'not-completed') . "'>" . ($finished ? \Yii::t('cd', 'Completed') : \Yii::t('cd', 'Not completed')) . "</td></tr>";
        $i++;
    }
?>
</table>
</div>
    </div>
    </div>
<?php
$this->endContent();
?>