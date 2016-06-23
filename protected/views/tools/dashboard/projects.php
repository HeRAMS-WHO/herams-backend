<div class="row">
<?php
/** @var \yii\web\View $this */
/** @var \prime\models\ar\Tool $tool */
echo $this->render('//projects/list.php', [
    'caption' => false,
    'projectsDataProvider' => $projectsDataProvider,
    'projectSearch' => $projectSearch,
    'hideToolColumn' => true

]);
?>
</div>