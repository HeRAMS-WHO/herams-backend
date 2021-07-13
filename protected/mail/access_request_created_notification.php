<?php
declare(strict_types=1);

use prime\models\ar\AccessRequest;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\mail\MessageInterface;
use yii\web\View;

/**
 * @var View $this
 * @var array $respondUrl
 * @var MessageInterface $message
 * @var AccessRequest $accessRequest
 */

if (empty($message->getSubject())) {
    $message->setSubject('HeRAMS access request created');
}

$fullRespondUrl = Url::to($respondUrl, true);

?>
<h1>New HeRAMS access request!</h1>

<p>
    A new access request was created to <strong><?= $accessRequest->target->title ?></strong> with subject <strong><?= $accessRequest->subject ?></strong>.
</p>
<p>
    <strong>
        <?= $accessRequest->response ?>
    </strong>
</p>
<p>
    To respond to the access request in HeRAMS click <?= Html::a('here', $fullRespondUrl) ?>.<br>
</p>

<div class="cta">
    <?= Html::a('Respond to access request', $fullRespondUrl) ?>
</div>

<p>
    If the links don't work please copy this into your browser:
</p>

<p>
    <?= $fullRespondUrl, true ?>
</p>

Thanks!<br>
<br>
- The HeRAMS Team
