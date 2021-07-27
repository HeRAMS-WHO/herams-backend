<?php
declare(strict_types=1);

use yii\helpers\Html;
use yii\helpers\Url;
use yii\mail\MessageInterface;
use yii\web\View;

/**
 * @var View $this
 * @var array $continueRoute
 * @var MessageInterface $message
 */

if (empty($message->getSubject())) {
    $message->setSubject('HeRAMS new access request');
}

$fullContinueUrl = Url::to($continueRoute, true);

?>
<h1>HeRAMS new access request</h1>

<p>
    A new access request has been created in HeRAMS to which you can respond.
</p>
<p>
    Click <?= Html::a('here', $fullContinueUrl) ?> to respond.<br>
</p>

<div class="cta">
    <?= Html::a('Respond to access request', $fullContinueUrl); ?>
</div>

<p>
    If the links don't work please copy this into your browser:
</p>

<p>
    <?= Url::to($fullContinueUrl, true); ?>
</p>

Thanks!<br>
<br>
- The HeRAMS Team
