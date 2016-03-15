<?php

use app\components\Html;
use Carbon\Carbon;

/**
 * @var \yii\web\View $this;
 * @var \prime\interfaces\ResponseCollectionInterface $responses
 * @var \prime\interfaces\SurveyCollectionInterface $surveys
 */

$this->registerAssetBundle(\prime\assets\SassAsset::class);
$this->beginContent('@app/views/layouts/report.php');

$responseFilter = new \prime\objects\ResponseFilter(iterator_to_array($responses));
$responseFilter->group('UOID');
$groups = $responseFilter->getGroups();
if(isset($groups[''])) {
    unset($groups['']);
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <h3><?=\Yii::t('app', 'Progress')?></h3>
        </div>
        <div class="col-xs-12">
            <table class="table">
                <tr>
                    <td><?=\Yii::t('app', 'Complete responses')?></td>
                    <td>
                        <?php
                        echo count($responses->filter(
                            function(\SamIT\LimeSurvey\Interfaces\ResponseInterface $response){
                                return null !== $response->getSurveyId();
                            })
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><?=\Yii::t('app', 'Incomplete responses')?></td>
                    <td>
                        <?php
                        echo count($responses->filter(
                            function(\SamIT\LimeSurvey\Interfaces\ResponseInterface $response){
                                return null === $response->getSurveyId();
                            })
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><?=\Yii::t('app', 'All responses')?></td>
                    <td>
                        <?php
                        echo count($responses);
                        ?>
                    </td>
                </tr>
            <?php if (!empty($groups)) { ?>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?=\Yii::t('app', 'Number of series')?></td>
                    <td>
                        <?php
                        echo count($groups);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><?=\Yii::t('app', 'Average responses per serie')?></td>
                    <td>
                        <?php
                        echo array_reduce($groups, function($carry, $item){return $carry + count($item);}) / count($groups);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><?=\Yii::t('app', 'All responses')?></td>
                    <td>
                        <?php
                        echo count($responses);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            <?php } ?>
                <tr>
                    <td><?=\Yii::t('app', 'Available languages')?></td>
                    <td><?php
                        $baseLanguages = [];
                        $languages = [];
                        /** @var \SamIT\LimeSurvey\Interfaces\SurveyInterface $survey */
                        foreach($surveys as $survey) {
                            $languages = array_merge($languages, array_flip($survey->getLanguages()));
                            $baseLanguages = array_merge($baseLanguages, [$survey->getDefaultLanguage() => true]);
                        }
                        if(count(array_diff_key($languages, $baseLanguages)) > 0) {
                            echo \Yii::t('app', 'Base language and additional languages');
                        } else {
                            echo \Yii::t('app', 'Base language');
                        }
                        ?></td>
                </tr>
                <tr>
                    <td><?=\Yii::t('app', 'Number of questions/groups')?></td>
                    <td><?php
                        $questionCount = 0;
                        $groupCount = 0;
                        /** @var \SamIT\LimeSurvey\Interfaces\SurveyInterface $survey */
                        foreach($surveys as $survey) {
                            /** @var \SamIT\LimeSurvey\Interfaces\GroupInterface $group */
                            foreach($survey->getGroups() as $group) {
                                $groupCount++;
                                $questionCount += count($group->getQuestions());
                            }
                        }
                        echo $questionCount . ' / ' . $groupCount;
                        ?></td>
                </tr>
                <tr>
                    <td><?=\Yii::t('app', 'Last update')?></td>
                    <td><?php
                        $maxDate = new Carbon('1900-01-01');
                        /** @var \SamIT\LimeSurvey\Interfaces\ResponseInterface $response */
                        foreach($responses as $response) {
                            $dates = [$response->getSubmitDate()];
                            if(isset($response->getData()['startdate'])) {
                                $dates[] = $response->getData()['startdate'];
                            }
                            if(isset($response->getData()['datestamp'])) {
                                $dates[] = $response->getData()['datestamp'];
                            }

                            foreach ($dates as $date) {
                                $date = new Carbon($date);
                                if($date->gt($maxDate)) {
                                    $maxDate = $date;
                                }
                            }
                        }
                        if($maxDate->gt(new Carbon('1900-01-01'))) {
                            echo $maxDate->format($this->context->dateFormat);
                        } else {
                            echo \Yii::t('app', 'Never updated');
                        }
                        ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php
$this->endContent();
?>