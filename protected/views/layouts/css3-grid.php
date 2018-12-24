<?php
/* @var $this \yii\web\View */
    /* @var $content string */
    $this->beginPage();

    $this->registerCssFile('/css/dashboard.css?' . time());
?>
<html>

<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<?php
    $this->head();
?>
</head>

<body>
<?php

$this->beginBody(); ?>
<div class="menu">
    <img src="https://herams.org/img/HeRAMS.png">
    <h1>PROJECT</h1>
    <nav>
        <a class="active" href="#">Overview</a>
        <section class="expanded">
            <a>Infrastructure</a>
            <a class="active" href="#">Descriptive</a>
            <a href="#">Basic amenities</a>
        </section>
        <section>
            <a>Infrastructure</a>
            <a class="active" href="#">Descriptive</a>
            <a href="#">Basic amenities</a>
        </section>
        <a href="#">Overview</a>
        <a href="#">Overview</a>

    </nav>
</div>

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