<?php

use prime\widgets\map\Map;
use rmrevin\yii\fontawesome\CdnFreeAssetBundle;
use yii\helpers\Html;

/* @var $this \yii\web\View */

/* @var $content string */
$this->beginPage();

//$this->registerCssFile('/css/dashboard.css?' . time());
$this->registerAssetBundle(CdnFreeAssetBundle::class);
$this->registerCssFile("https://fonts.googleapis.com/css?family=Source+Sans+Pro");
$this->registerAssetBundle(\prime\assets\NewAppAsset::class);
?>
    <!doctype HTML>
    <html lang="<?=\Yii::$app->language; ?>">

    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <?= $this->head();?>

        <style>
            body {
                margin: 0;
                min-height: 100vh;
            }

            .map {
                visibility: hidden;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                top: 0;
                z-index: 1;
            }

            @media (min-width: 710px) {
                body {
                    display: flex;
                    margin: 0;
                    align-items: center;
                    justify-content: center;
                }
                .map {
                    visibility: visible;
                }

                .map:after {
                    content: " ";
                    position: fixed;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    top: 0;
                    background-color: rgba(0, 0, 0, 0.5);
                    pointer-events: none;
                    z-index: 400;
                }
            }

            .popover {
                --color: #505050;
                --padding: 40px;
                --gutter-color: grey;
                --gutter-width: 1px;
                --dark-background-color: #424348;
                --darker-background-color: #222328;
                --dark-color: white;
                --status-height: 35px;
                --darker-color: var(--dark-color);
                --primary-button-background-color: #4177c1;
                --link-color: #4177c1;
                --primary-button-color: white;
                --validation-error-color: #d90001;
                --border-color: #7c7b80;
                --stat-icon-color: #a09fa4;
                z-index: 2;
                background-color: white;
                display: grid;
                max-width: 710px;
                grid-template-columns: 1fr;
                grid-template-rows: 1fr auto auto var(--status-height);
                grid-template-areas:
                        "intro"
                        "login"
                        "stat"
                        "status"
            ;

            }

            .form {
                grid-area: login;
                padding: var(--padding);
                padding-bottom: 0;
                margin-bottom: var(--padding);
                position: relative;
                display: flex;
                flex-direction: column;
            }

            .form > * {
                flex-grow: 0;
            }

            .form > form {
                flex-grow: 1;
            }

            .intro {
                padding: var(--padding);
                padding-top: 0;
                color: #565656;
                grid-area: intro;
                text-align: justify;
                margin: 0;
                line-height: 2em;
                font-size: 16px;
                padding-bottom: 0;
            }

            .intro img {
                max-width: 200px;
                display: block;
                margin: auto;
            }

            @media (min-width: 710px) {
                .popover {
                    padding-top: var(--padding);
                    border-radius: 3px;

                    grid-template-columns: 1fr 1fr;
                    grid-template-rows: 1fr auto var(--status-height);
                    grid-template-areas:
                            "intro  login"
                            "stat   stat"
                            "status status"
                ;
                }

                .intro {
                    border-right: var(--gutter-width) solid var(--gutter-color);
                    margin-bottom: var(--padding);
                    padding-bottom: 0;
                }

                .form {

                    flex-grow: 0;
                }

                .intro img {
                    max-width: calc(100% - 64px);
                }
            }

            .actions {
                flex-wrap: wrap;
                display: flex;
                justify-content: space-between;
            }






            .popover a {
                font-size: 13px;
                color: var(--link-color);
                display: block;
                margin-bottom: 5px;
            }
            .popover header {
                font-size: 24px;
                color: #353535;
                font-weight: 600;
            }

            .stats {
                display: flex;
                flex-wrap: wrap;
                grid-area: stat;
                padding-top: 25px;
                padding-bottom: 25px;
                background-color: var(--dark-background-color);
            }
            .stat {
                flex-grow: 1;
                flex-basis: 0;
                color: var(--dark-color);
                text-align: center;
                font-size: 16px;
                border-right: calc(var(--gutter-width) / 2) solid var(--gutter-color);
                /*border-left: calc(var(--gutter-width) / 2) solid var(--gutter-color);*/
            }

            .stat:first-child {
                border-left: none;
            }

            .stat:last-child {
                border-right: none;
            }

            .stat svg {
                color: var(--stat-icon-color);
                font-size: 40px;
                display: block;
                margin: auto;
            }
            .stat span {
                display: block;
                margin: auto;
                font-size: 48px;
            }

            .status {
                line-height: var(--status-height);
                text-align: center;
                font-size: 16px;
                grid-area: status;
                font-weight: bold;
                background-color: #222229;
                color: #ffffff;
            }

            .status .value {
                color: #92929b;
                font-size: 14px;


                font-weight: normal;
            }

            .status .icon {
                vertical-align: middle;
            }

        </style>
    </head>


    <body>

    <?php

    $this->beginBody();
    echo $this->render('//flash.php');
    echo Map::widget();
    echo $content;
    $this->endBody();
    ?>
    </body>

    </html>
<?php
$this->endPage();