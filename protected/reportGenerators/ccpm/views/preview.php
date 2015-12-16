<?php

use app\components\Html;
use yii\helpers\ArrayHelper;
use app\components\Form;

/**
 * @var \prime\models\ar\UserData $userData
 * @var \yii\web\View $this
 * @var \prime\reportGenerators\ccpm\Generator $generator
 * @var \prime\interfaces\ProjectInterface $project
 * @var \prime\interfaces\SignatureInterface $signature
 * @var \prime\interfaces\ResponseCollectionInterface $responses
 */

$generator = $this->context;

$scores = [
    '1.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q111', 'q112', 'q114', 'q118', 'q113', 'q115', 'q119', 'q116', 'q117'], $generator->PPASurveyId => ['q111', 'q112', 'q113', 'q114', 'q115', 'q116']], 'average'),
    '1.1.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q111'], $generator->PPASurveyId => []]),
    '1.1.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q112'], $generator->PPASurveyId => ['q111']]),
    '1.1.3' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q114'], $generator->PPASurveyId => ['q112']]),
    '1.1.4' => $generator->calculateScore($responses, [$generator->CPASurveyId => [], $generator->PPASurveyId => ['q113']]),
    '1.1.5' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q118'], $generator->PPASurveyId => ['q114']]),
    '1.1.6' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q113'], $generator->PPASurveyId => []]),
    '1.1.7' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q115'], $generator->PPASurveyId => ['q115']]),
    '1.1.8' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q119'], $generator->PPASurveyId => ['q116']]),
    '1.1.9' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q116'], $generator->PPASurveyId => []]),
    '1.1.10' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q117'], $generator->PPASurveyId => []]),
    '1.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q121', 'q122', 'q123'], $generator->PPASurveyId => ['q121', 'q122', 'q123']], 'average'),
    '1.2.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q121'], $generator->PPASurveyId => []]),
    '1.2.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q122'], $generator->PPASurveyId => ['q121']]),
    '1.2.3' => $generator->calculateScore($responses, [$generator->CPASurveyId => [], $generator->PPASurveyId => ['q122']]),
    '1.2.4' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q123'], $generator->PPASurveyId => ['q123']]),
    '2.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q211', 'q212', 'q213'], $generator->PPASurveyId => ['q211', 'q212', 'q213']], 'average'),
    '2.1.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q211'], $generator->PPASurveyId => ['q211']]),
    '2.1.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q212'], $generator->PPASurveyId => ['q212']]),
    '2.1.3' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q213'], $generator->PPASurveyId => ['q213']]),
    '2.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q221', 'q222[1]', 'q222[2]', 'q222[3]', 'q222[4]', 'q222[5]', 'q223[1]', 'q223[2]', 'q223[3]', 'q223[4]', 'q223[5]', 'q223[6]', 'q223[7]', 'q223[8]'], $generator->PPASurveyId => ['q221', 'q222[1]', 'q222[2]', 'q222[3]', 'q222[4]', 'q222[5]', 'q223[1]', 'q223[2]', 'q223[3]', 'q223[4]', 'q223[5]', 'q223[6]', 'q223[7]', 'q223[8]']], 'average'),
    '2.2.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q221'], $generator->PPASurveyId => ['q221']]),
    '2.2.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q222[1]'], $generator->PPASurveyId => ['q222[1]']]),
    '2.2.3' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q222[2]'], $generator->PPASurveyId => ['q222[2]']]),
    '2.2.4' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q222[3]'], $generator->PPASurveyId => ['q222[3]']]),
    '2.2.5' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q222[4]'], $generator->PPASurveyId => ['q222[4]']]),
    '2.2.6' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q222[5]'], $generator->PPASurveyId => ['q222[5]']]),
    '2.2.7' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q223[1]'], $generator->PPASurveyId => ['q223[1]']]),
    '2.2.8' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q223[2]'], $generator->PPASurveyId => ['q223[2]']]),
    '2.2.9' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q223[3]'], $generator->PPASurveyId => ['q223[3]']]),
    '2.2.10' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q223[4]'], $generator->PPASurveyId => ['q223[4]']]),
    '2.2.11' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q223[5]'], $generator->PPASurveyId => ['q223[5]']]),
    '2.2.12' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q223[6]'], $generator->PPASurveyId => ['q223[6]']]),
    '2.2.13' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q223[7]'], $generator->PPASurveyId => ['q223[7]']]),
    '2.2.14' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q223[8]'], $generator->PPASurveyId => ['q223[8]']]),
    '2.3' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q231'], $generator->PPASurveyId => ['q231']], 'average'),
    '2.3.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q231'], $generator->PPASurveyId => ['q231']]),
    '3.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q311', 'q314', 'q312', 'q313', 'q315[1]', 'q315[2]', 'q315[3]', 'q315[4]', 'q315[5]', 'q315[6]', 'q315[7]', 'q315[8]', 'q316', 'q317', 'q318'], $generator->PPASurveyId => ['q311', 'q312']], 'average'),
    '3.1.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q311'], $generator->PPASurveyId => []]),
    '3.1.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q314'], $generator->PPASurveyId => ['q311']]),
    '3.1.3' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q312'], $generator->PPASurveyId => []]),
    '3.1.4' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q313'], $generator->PPASurveyId => []]),
    '3.1.5' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q315[1]'], $generator->PPASurveyId => []]),
    '3.1.6' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q315[2]'], $generator->PPASurveyId => []]),
    '3.1.7' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q315[3]'], $generator->PPASurveyId => []]),
    '3.1.8' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q315[4]'], $generator->PPASurveyId => []]),
    '3.1.9' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q315[5]'], $generator->PPASurveyId => []]),
    '3.1.10' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q315[6]'], $generator->PPASurveyId => []]),
    '3.1.11' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q315[7]'], $generator->PPASurveyId => []]),
    '3.1.12' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q315[8]'], $generator->PPASurveyId => []]),
    '3.1.13' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q316'], $generator->PPASurveyId => []]),
    '3.1.14' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q317'], $generator->PPASurveyId => ['q312']]),
    '3.1.15' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q318'], $generator->PPASurveyId => []]),
    '3.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q321', 'q322'], $generator->PPASurveyId => ['q321']], 'average'),
    '3.2.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q321'], $generator->PPASurveyId => []]),
    '3.2.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q322'], $generator->PPASurveyId => ['q321']]),
    '3.3' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q331', 'q332', 'q333', 'q334'], $generator->PPASurveyId => ['q331', 'q332', 'q333']], 'average'),
    '3.3.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q331'], $generator->PPASurveyId => ['q331']]),
    '3.3.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q332'], $generator->PPASurveyId => ['q332']]),
    '3.3.3' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q333'], $generator->PPASurveyId => []]),
    '3.3.4' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q334'], $generator->PPASurveyId => ['q333']]),
    '4.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q411'], $generator->PPASurveyId => ['q411']], 'average'),
    '4.1.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q411'], $generator->PPASurveyId => ['q411']]),
    '4.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q421'], $generator->PPASurveyId => ['q421']], 'average'),
    '4.2.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q421'], $generator->PPASurveyId => ['q421']]),
    '5' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q51', 'q52', 'q53', 'q54', 'q55', 'q56'], $generator->PPASurveyId => ['q51', 'q52', 'q53']], 'average'),
    '5.1.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q51'], $generator->PPASurveyId => ['q52']]),
    '5.1.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q52'], $generator->PPASurveyId => []]),
    '5.1.3' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q53'], $generator->PPASurveyId => []]),
    '5.1.4' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q54'], $generator->PPASurveyId => []]),
    '5.1.5' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q55'], $generator->PPASurveyId => ['q51']]),
    '5.1.6' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q56'], $generator->PPASurveyId => ['q53']]),
    '6' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q61', 'q62', 'q63', 'q64', 'q65', 'q66'], $generator->PPASurveyId => ['q61', 'q62']], 'average'),
    '6.1.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q61'], $generator->PPASurveyId => []]),
    '6.1.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q62'], $generator->PPASurveyId => []]),
    '6.1.3' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q63'], $generator->PPASurveyId => ['q61']]),
    '6.1.4' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q64'], $generator->PPASurveyId => ['q62']]),
    '6.1.5' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q65'], $generator->PPASurveyId => []]),
    '7' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q71', 'q72'], $generator->PPASurveyId => ['q71', 'q72']], 'average'),
    '7.1.1' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q71'], $generator->PPASurveyId => ['q71']]),
    '7.1.2' => $generator->calculateScore($responses, [$generator->CPASurveyId => ['q72'], $generator->PPASurveyId => ['q72']]),
];

$distributions = [
    '1.1.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q111'], $generator->PPASurveyId => []]),
    '1.1.2' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q112'], $generator->PPASurveyId => ['q111']]),
    '1.1.3' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q114'], $generator->PPASurveyId => ['q112']]),
    '1.1.4' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => [], $generator->PPASurveyId => ['q113']]),
    '1.1.5' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q118'], $generator->PPASurveyId => ['q114']]),
    '1.1.6' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q113'], $generator->PPASurveyId => []]),
    '1.1.7' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q115'], $generator->PPASurveyId => ['q115']]),
    '1.1.8' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q119'], $generator->PPASurveyId => ['q116']]),
    '1.1.9' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q116'], $generator->PPASurveyId => []]),
    '1.1.10' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q117'], $generator->PPASurveyId => []]),
    '1.2.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q121'], $generator->PPASurveyId => []]),
    '1.2.2' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q122'], $generator->PPASurveyId => ['q121']]),
    '1.2.3' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => [], $generator->PPASurveyId => ['q122']]),
    '1.2.4' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q123'], $generator->PPASurveyId => ['q123']]),
    '2.1.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q211'], $generator->PPASurveyId => ['q211']]),
    '2.1.2' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q212'], $generator->PPASurveyId => ['q212']]),
    '2.1.3' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q213'], $generator->PPASurveyId => ['q213']]),
    '2.2.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q221'], $generator->PPASurveyId => ['q221']]),
    '2.2.2' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q222[1]'], $generator->PPASurveyId => ['q222[1]']]),
    '2.2.3' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q222[2]'], $generator->PPASurveyId => ['q222[2]']]),
    '2.2.4' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q222[3]'], $generator->PPASurveyId => ['q222[3]']]),
    '2.2.5' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q222[4]'], $generator->PPASurveyId => ['q222[4]']]),
    '2.2.6' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q222[5]'], $generator->PPASurveyId => ['q222[5]']]),
    '2.2.7' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q223[1]'], $generator->PPASurveyId => ['q223[1]']]),
    '2.2.8' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q223[2]'], $generator->PPASurveyId => ['q223[2]']]),
    '2.2.9' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q223[3]'], $generator->PPASurveyId => ['q223[3]']]),
    '2.2.10' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q223[4]'], $generator->PPASurveyId => ['q223[4]']]),
    '2.2.11' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q223[5]'], $generator->PPASurveyId => ['q223[5]']]),
    '2.2.12' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q223[6]'], $generator->PPASurveyId => ['q223[6]']]),
    '2.2.13' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q223[7]'], $generator->PPASurveyId => ['q223[7]']]),
    '2.2.14' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q223[8]'], $generator->PPASurveyId => ['q223[8]']]),
    '2.3.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q231'], $generator->PPASurveyId => ['q231']]),
    '3.1.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q311'], $generator->PPASurveyId => []]),
    '3.1.2' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q314'], $generator->PPASurveyId => ['q311']]),
    '3.1.3' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q312'], $generator->PPASurveyId => []]),
    '3.1.4' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q313'], $generator->PPASurveyId => []]),
    '3.1.5' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q315[1]'], $generator->PPASurveyId => []]),
    '3.1.6' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q315[2]'], $generator->PPASurveyId => []]),
    '3.1.7' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q315[3]'], $generator->PPASurveyId => []]),
    '3.1.8' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q315[4]'], $generator->PPASurveyId => []]),
    '3.1.9' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q315[5]'], $generator->PPASurveyId => []]),
    '3.1.10' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q315[6]'], $generator->PPASurveyId => []]),
    '3.1.11' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q315[7]'], $generator->PPASurveyId => []]),
    '3.1.12' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q315[8]'], $generator->PPASurveyId => []]),
    '3.1.13' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q316'], $generator->PPASurveyId => []]),
    '3.1.14' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q317'], $generator->PPASurveyId => ['q312']]),
    '3.1.15' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q318'], $generator->PPASurveyId => []]),
    '3.2.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q321'], $generator->PPASurveyId => []]),
    '3.2.2' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q322'], $generator->PPASurveyId => ['q321']]),
    '3.3.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q331'], $generator->PPASurveyId => ['q331']]),
    '3.3.2' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q332'], $generator->PPASurveyId => ['q332']]),
    '3.3.3' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q333'], $generator->PPASurveyId => []]),
    '3.3.4' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q334'], $generator->PPASurveyId => ['q333']]),
    '4.1.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q411'], $generator->PPASurveyId => ['q411']]),
    '4.2.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q421'], $generator->PPASurveyId => ['q421']]),
    '5.1.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q51'], $generator->PPASurveyId => ['q52']]),
    '5.1.2' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q52'], $generator->PPASurveyId => []]),
    '5.1.3' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q53'], $generator->PPASurveyId => []]),
    '5.1.4' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q54'], $generator->PPASurveyId => []]),
    '5.1.5' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q55'], $generator->PPASurveyId => ['q51']]),
    '5.1.6' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q56'], $generator->PPASurveyId => ['q53']]),
    '6.1.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q61'], $generator->PPASurveyId => []]),
    '6.1.2' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q62'], $generator->PPASurveyId => []]),
    '6.1.3' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q63'], $generator->PPASurveyId => ['q61']]),
    '6.1.4' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q64'], $generator->PPASurveyId => ['q62']]),
    '6.1.5' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q65'], $generator->PPASurveyId => []]),
    '7.1.1' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q71'], $generator->PPASurveyId => ['q71']]),
    '7.1.2' => $generator->calculateDistribution($responses, [$generator->CPASurveyId => ['q72'], $generator->PPASurveyId => ['q72']]),
];

$this->beginContent('@app/views/layouts/report.php');
?>
<style>
    <?=file_get_contents(__DIR__ . '/../../base/assets/css/grid.css')?>
    <?php include __DIR__ . '/../../base/assets/css/style.php'; ?>
    .background-good, .background-satisfactory, .background-unsatisfactory, .background-weak {
        font-weight: 600;
    }

    .background-good {
        background-color: #1fc63c;
        color: white;
    }

    .background-satisfactory {
        background-color: #ffe003;
        color: white;
    }

    .background-unsatisfactory {
        background-color: #ff9400;
        color: white;
    }

    .background-weak {
        background-color: red;
        color: white;
    }

    .text-good {
        color: #1fc63c;
    }

    .text-satisfactory {
        color: #ffe003;
    }

    .text-unsatisfactory {
        color: #ff9400;
    }

    .text-weak {
        color: red;
    }
</style>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h1 class="col-xs-12"><?=$project->getLocality()?></h1>
    </div>
    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            \Yii::t('ccpm', 'Level : {level}', ['level' => 'National']) . '<br>' . \Yii::t('ccpm', 'Completed on: {completedOn}', ['completedOn' => $signature->getTime()->format('d F - Y')]),
        ],
        'columnsInRow' => 2
    ]);
    ?>
    <hr>
    <div class="row">
        <h1 style="margin-top: 300px; margin-bottom: 300px; text-align: center;"><?=\Yii::t('ccpm', 'Final report')?></h1>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <div class="col-xs-12">
        <h2><?=\Yii::t('ccpm', 'Overall response rate')?><span style="font-size: 0.5em; margin-left: 50px;">(<?=Yii::t('ccpm', 'Based on the number of organizations that are part of the cluster')?></span></h2>
        </div>
    </div>
    <?php
    $responseRates = $generator->getResponseRates($responses);
    ?>
    <?=\prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates['total']['total1'], 'part' => $responseRates['total']['responses'], 'view' => $this])?>
    <?php
    $graphWidth = 3;
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[1]['total1'], 'part' => $responseRates[1]['responses'], 'title' => Yii::t('ccpm', 'International NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[2]['total1'], 'part' => $responseRates[2]['responses'], 'title' => Yii::t('ccpm', 'National NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[3]['total1'], 'part' => $responseRates[3]['responses'], 'title' => Yii::t('ccpm', 'UN Agencies'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[4]['total1'], 'part' => $responseRates[4]['responses'], 'title' => Yii::t('ccpm', 'National Authorities'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[5]['total1'], 'part' => $responseRates[5]['responses'], 'title' => Yii::t('ccpm', 'Donors'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[6]['total1'], 'part' => $responseRates[6]['responses'], 'title' => Yii::t('ccpm', 'Other'), 'graphWidth' => $graphWidth, 'view' => $this]),
        ],
        'columnsInRow' => 2
    ]);

    ?>
</div>

    <div class="container-fluid">
        <?=$this->render('header', ['project' => $project])?>
        <div class="row">
            <div class="col-xs-12">
                <h2><?=\Yii::t('ccpm', 'Overall response rate 2')?><span style="font-size: 0.5em; margin-left: 50px;">(<?=Yii::t('ccpm', 'Based on the number of organizations that are part of the cluster')?></span></h2>
            </div>
        </div>
        <?php
        $responseRates = $generator->getResponseRates($responses);
        ?>
        <?=\prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates['total']['total2'], 'part' => $responseRates['total']['responses'], 'view' => $this])?>
        <?php
        $graphWidth = 3;
        echo \prime\widgets\report\Columns::widget([
            'items' => [
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[1]['total2'], 'part' => $responseRates[1]['responses'], 'title' => Yii::t('ccpm', 'International NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[2]['total2'], 'part' => $responseRates[2]['responses'], 'title' => Yii::t('ccpm', 'National NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[3]['total2'], 'part' => $responseRates[3]['responses'], 'title' => Yii::t('ccpm', 'UN Agencies'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[4]['total2'], 'part' => $responseRates[4]['responses'], 'title' => Yii::t('ccpm', 'National Authorities'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[5]['total2'], 'part' => $responseRates[5]['responses'], 'title' => Yii::t('ccpm', 'Donors'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[6]['total2'], 'part' => $responseRates[6]['responses'], 'title' => Yii::t('ccpm', 'Other'), 'graphWidth' => $graphWidth, 'view' => $this]),
            ],
            'columnsInRow' => 2
        ]);

        ?>
    </div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('ccpm', 'Overall Performance')?></h2>
    </div>
    <?php

    $performanceStatusBlockColumns = [
        'items' => [
            [
                'content' => \Yii::t('ccpm', 'Score') . '<hr>> 75 %<br>51 % - 75 %<br>26 % - 50 %<br>< 26 %',
                'width' => 6
            ],
            [
                'content' => \Yii::t('ccpm', 'Performance status') . '<hr><span class="text-good">' . \Yii::t('ccpm', 'Good') . '</span><br><span class="text-satisfactory">' . \Yii::t('ccpm', 'Satisfactory') . '</span><br><span class="text-unsatisfactory">' . \Yii::t('ccpm', 'Unsatisfactory') . '</span><br><span class="text-weak">' . \Yii::t('ccpm', 'Weak') . '</span>',
                'width' => 6
            ]
        ],
        'columnsInRow' => 12
    ];

    $performanceStatusBlock =
        '<div class="col-xs-12" style="border: 1px solid black; padding-top: 15px; padding-bottom: 15px;">' . \prime\widgets\report\Columns::widget($performanceStatusBlockColumns) . '</div>';

    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => $performanceStatusBlock,
                'width' => 4
            ],
            [
                'content' => $this->render('performanceStatusTable', ['generator' => $generator, 'scores' => $scores]),
                'width' => 8
            ],
        ],
        'columnsInRow' => 12
    ]);
    ?>
</div>

<?=$this->render('functionsAndReview', ['generator' => $generator, 'scores' => $scores, 'project' => $project, 'userData' => $userData])?>
<?=$this->render('distributions', ['generator' => $generator, 'distributions' => $distributions, 'project' => $project, 'userData' => $userData], $this)?>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('ccpm', 'Comments')?></h2>
    </div>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'General') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => [], $generator->PPASurveyId => ['q014']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Supporting service delivery') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q124'], $generator->PPASurveyId => ['q124']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Informing strategic decision-making of the Humanitarian Coordinator/Humanitarian Country Team') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q232'], $generator->PPASurveyId => ['q232']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Planning and strategy development') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q335'], $generator->PPASurveyId => ['q334']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Advocacy') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q422'], $generator->PPASurveyId => ['q422']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Monitoring and reporting on implementation of cluster strategy and results') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q57'], $generator->PPASurveyId => ['q54']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Preparedness for recurrent disasters') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q66'], $generator->PPASurveyId => ['q63']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Accountability to affected populations') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q73'], $generator->PPASurveyId => ['q73']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Others') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q81'], $generator->PPASurveyId => ['q81']], function($value){return !empty($value);})
        ]
    ])?>
</div>
<?php $this->endContent(); ?>