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
 * @var bool $partial
 */

if (empty($message->getSubject())) {
    $message->setSubject('HeRAMS access request granted');
}

$fullContinueUrl = Url::to($continueRoute, true);

?>
<h1>HeRAMS access request!</h1>

<p>
    You requested access to <strong><?= $accessRequest->target->title ?></strong> with subject <strong><?= $accessRequest->subject ?></strong>.
</p>
<p>
    This request was <?= $partial ? 'partially' : '' ?> granted implicitly.
</p>
<?php if ($partial) { ?>
<p>
    The grant was implicit, meaning your access request was covered by some other access request or an someone added you manually. If the granted permission is not enough, please request the extra permissions again.
</p>
<?php } ?>

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
