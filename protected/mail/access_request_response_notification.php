<?php
declare(strict_types=1);

use prime\models\ar\AccessRequest;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\mail\MessageInterface;
use yii\web\View;

/**
 * @var View $this
 * @var array $continueRoute
 * @var MessageInterface $message
 * @var AccessRequest $accessRequest
 */

if (empty($message->getSubject())) {
    if ($accessRequest->accepted) {
        $message->setSubject('HeRAMS access request accepted');
    } else {
        $message->setSubject('HeRAMS access request rejected');
    }
}

$fullContinueUrl = Url::to($continueRoute, true);

?>
<h1>HeRAMS access request!</h1>

<p>
    You requested access to <strong><?= $accessRequest->target->title ?></strong> with subject <strong><?= $accessRequest->subject ?></strong>.
</p>
<p>
    This request was <?= $accessRequest->accepted ? 'accepted' : 'rejected'?>.
</p>
<p>
    The message with the response was:
</p>
<p>
    <strong>
        <?= $accessRequest->response ?>
    </strong>
</p>
<p>
    To continue to HeRAMS click <?= Html::a('here', $fullContinueUrl) ?>.<br>
</p>

<div class="cta">
    <?= Html::a('To HeRAMS', $fullContinueUrl); ?>
</div>

<p>
    If the links don't work please copy this into your browser:
</p>

<p>
    <?= $fullContinueUrl ?>
</p>

Thanks!<br>
<br>
- The HeRAMS Team
