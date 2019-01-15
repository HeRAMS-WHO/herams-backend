<?php
/* @var $this \yii\web\View */
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

$this->beginBody(); ?>
<div class="user-menu">
    <i class="fas fa-chevron-down"></i>
    <img src="https://herams.org/img/Profile_white.png">
    <div>
        <div class="name">Sam Mousa</div>
        <div class="email">sam@mousa.nl</div>
    </div>
</div>
<div class="breadcrumbs">
    <div class="parent">Status &gt; </div>
    <div class="current">Functionality</div>
</div>
<?php
    echo $content;
    $this->endBody();
?>
</body>

</html>
<?php
    $this->endPage();