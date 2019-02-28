<?php
/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $content string */
    $this->beginPage();

    $this->registerCssFile('/css/dashboard.css?' . time());
?>
<html>

<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha256-l85OmPOjvil/SOvVt3HnSSjzF1TUMyT9eV0c2BzEGzU=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.2/flatpickr.min.css" integrity="sha256-TV6wP5ef/UY4bNFdA1h2i8ASc9HHcnl8ufwk94/HP4M=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.2/flatpickr.min.js" integrity="sha256-44TeE1bzEP4BfpL6Wb05CVgLDKN6OzOAI79XNMPR4Bs=" crossorigin="anonymous"></script>
<?php
    $this->head();
?>
</head>

<body>
<?php

$this->beginBody();

?>
<div class="user-menu">
    <i class="fas fa-chevron-down"></i>
    <?php
        /** @var \prime\models\ar\User $user */
        $user = \Yii::$app->user->identity;
        echo Html::img($user->getGravatarUrl(), [
            'referrerpolicy' => 'no-referrer'
        ]);

    ?>

    <div>
        <?= Html::a("{$user->firstName} {$user->lastName}", ['/user/settings/profile'], [
                'class' => 'name'
        ]); ?>
        <div class="email"><?= $user->email ?></div>
    </div>
</div>
<div class="title">
<?php
    echo '<!-- Breadcrumbs -->' . Breadcrumbs::widget([
        'homeLink' => [
            'label' => \Yii::t('app', 'World overview'),
            'url' => '/'
        ],
        'links' => $this->params['breadcrumbs'] ?? []
    ]);
echo Html::tag('span', $this->title, ['class' => 'header']);
?></div>
<?php
    echo $content;
    $this->endBody();
?>
</body>

</html>
<?php
    $this->endPage();