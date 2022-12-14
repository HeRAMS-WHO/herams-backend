<?php

declare(strict_types=1);

use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var \yii\mail\MessageInterface $message
 * @var string $url
 */

if (empty($message->getSubject())) {
    $message->setSubject('Please confirm your new email address');
}

?>
Please click <?= Html::a('here', $url) ?> to confirm your new email address.

