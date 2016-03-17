<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 */

$this->params['brandLabel'] = Html::img('@web/img/logo_Prime_BKGD_tag_RVB.png');

?>
<div class="row">
    <?=Html::img('@web/img/MapMonde_RVB.svg', [
        'class' => ['col-md-12'],
        'style' => [
            'margin-top' => '10px'
        ]
    ])?>
</div>

</div>
<div style="background-color: #f8f8f8; border: 1px solid #e7e7e7;margin-top: 30px;">
<div class="container">

<div class="row" style="padding-top: 15px; margin-bottom: 30px;">
    <div class="col-sm-12">
        <h1 style="margin-top: 0px;">About Prime</h1>
    </div>
    <div class="col-sm-12 col-md-6">
        <p>
            Prime supports timely and evidence-based decision making in humanitarian emergencies by providing health
            sector actors involved in the response with a platform for standardised production and exchange of critical
            information.
        </p>
        <p>
            Prime drives the production of information through a series of tools that significantly facilitate and
            reinforce all aspects of information management, including data collection, management, analysis, reporting,
            sharing and dissemination.
        </p>
        <?php
        if (\Yii::$app->user->isGuest) {
            echo Html::a(\Yii::t('app', 'Login or sign up'), ['user/login'], [
                'class' => 'btn btn-default btn-prime',
                'style' => 'display: block; margin-top: 10px;'
            ]);
        }
        ?>
    </div>
    <style>
        body {
            font-size: 2em;
        }
        ul#prime-usp {
            position: relative;
            list-style-type: none;
            padding-left: 0;
        }
        /*img {*/
            /*position: absolute;*/
            /*left: 0px;*/
            /*height: 1em;*/
        /*}*/

        #prime-usp li {
            text-align: justify;
            margin-bottom: 10px;
        }
        #prime-usp li img {
            width: 10%;
            padding-right: 10px;
        }
        #prime-usp li div {
            width: 90%;
        }
        #prime-usp li img, li div {
            display: inline-block;
            vertical-align: middle;;
        }
    </style>
    <div class="col-sm-12 col-md-6">
        <span style="margin-bottom: 5px;display: inline-block;">PRIME aims more particularly at improving:</span>
        <ul id="prime-usp">
        <li><?=Html::img('@web/img/picto_Reliability.svg'); ?><div>The reliability of the information through improved transparency and wider participation</div></li>
        <li><?=Html::img('@web/img/picto_Consistency.svg'); ?><div>The consistency of the information by ensuring up to date and reliable information is made available at any point in time during an emergency</div></li>
        <li><?=Html::img('@web/img/picto_Efficiency.svg'); ?><div>The timeliness and efficiency of information management by offering efficient and easily-accessible data collection, management, analysis and reporting services</div></li>
        <li><?=Html::img('@web/img/picto_Efficiency.svg'); ?><div>The access to information by supporting improved sharing and dissemination practices</div></li>
        </ul>
    </div>
</div>

</div>